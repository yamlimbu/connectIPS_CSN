<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\EventRegisterRequest;
use App\Services\ApiService;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StringHelper;
use App\Models\EventRegistrationHold;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\EventCategoryTicket;
use App\Models\EventCategoryTicketPrice;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\EventRegistrationPaymentOrder;
use App\Models\EventRegistration;
use App\Mail\EventCodeMail;
use Illuminate\Support\Facades\Mail;
Use App\Services\EventService;
use App\Http\Controllers\Api\ConnectIPSGatewayController;
use App\Services\ConnectIpsService;
use App\Helpers\RecordHelper;
use Illuminate\Support\Facades\Log;
class EventController extends Controller
{
    protected $apiService;
    protected $connectIpsService;
    protected $connectIPSGatewayController;

    public function __construct(ApiService $apiService,
    EventService $eventService, ConnectIpsService $connectIpsService, ConnectIPSGatewayController $connectIPSGatewayController)
    {
        $this->apiService = $apiService;
        $this->eventService = $eventService;
        $this->connectIpsService = $connectIpsService;
        $this->connectIPSGatewayController = $connectIPSGatewayController;

    }


    public function index()
    {
        // Fetch data from the API
        $response = $this->apiService->get('/events');


        // Decode the JSON response
        $events = $response->json('data');

        // Pass data to the view
        return view('welcome', compact('events'));
    }


    public function register($event_id)
    {
        // Fetch data from DB
        $data = Event::with([
            'eventCategories' => function ($query) {
                $query->orderBy('id')->with([
                    'tickets' => function ($query) {
                        $query->orderBy('id')->with([
                            'prices' => function ($query) {
                                $query->orderBy('id');
                            }
                        ]);
                    }
                ]);
            }
        ])->findOrFail($event_id);


        // Pass data to the view
        return view('register', compact('data', 'event_id'));
    }

    public function details($event_id)
    {
        // Fetch data from the API
        $response = $this->apiService->get('/events');


        // Decode the JSON response
        $data = $response->json('data');
        // Pass data to the view
        return view('event_details', compact('data', 'event_id'));
    }





    function event_register(EventRegisterRequest $request)
    {
        if ($request->registration_form == 'submitted') {
            $validatedData = $request->validated();

            //return redirect(route('preview'));
            $request->session()->put('data', $validatedData);

            return redirect()->route('preview');
        }
    }

    function final_submit(Request $request)
    {
        if ($request->registration_form == 'submitted') {


            $postdata = [
                'event_id' => $request->input('event_id'),
                'nmc_registration_number' => $request->input('nmc_registration_number'),
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'email_address' => $request->input('email_address'),
                'phone_number' => $request->input('phone_number'),
                'event_category_ticket_ids[0]' => 1,
                'event_category_ticket_ids[1]' => 3,
                'payment_method' => $request->input('payment_method'),
                'payment_receipt' => $request->input('payment_receipt'),
                'payment_status' => $request->input('payment_status'),

                'total_amount' => $request->input('total_amount'),
                'transaction_id' => $request->input('transaction_id'),
            ];

            return redirect(route('success'));
        }
    }



    public function preview(Request $request)
    {
        // Return the success view
        $merchantid = config('app.merchantid');
        $appid = config('app.appid');
        $appname = config('app.appname');
        $txncrncy = config('app.txncrncy');
        $payment_data = session('data');

        // Check if $paymentData is not empty
        if (empty($payment_data)) {
            return redirect()->route('event.register', ['event_id' => 1])->withErrors('No payment data found for preview.');

        }
        $nmc_registration_number = $payment_data['nmc_registration_number'];
        $first_name = $payment_data['first_name'];
        $middle_name = $payment_data['middle_name'];
        $last_name = $payment_data['last_name'];
        $email_address = $payment_data['email_address'];
        $phone_number = $payment_data['phone_number'];

        $payment_method = 'connectIPS';
        $currentDate = Carbon::now()->format('d-m-Y');
        $event_id = 1;
        $event = Event::find($event_id);
        $response = $this->apiService->get('/event/1/tickets');
        // Decode the JSON response
        $tickets = $response->json('data');

        $event_category_ticket_prices_ids = array_map('intval', $payment_data['event_category_ticket_prices_ids']);

        $eventCategoryTicketPriceIds = [];
        $eventCategoryIds = [];
        $EventCategoryTicketIds = [];

        foreach ($event_category_ticket_prices_ids as $event_category_ticket_prices_id) {
            // Fetch the event category ticket price based on $id
            $eventCategoryTicketPrice= EventCategoryTicketPrice::find($event_category_ticket_prices_id);
            $EventCategoryTicket = EventCategoryTicket::find($eventCategoryTicketPrice->event_category_ticket_id);
            $eventCategoryIds[] = $EventCategoryTicket->event_category_id;
            $EventCategoryTicketIds[] = $EventCategoryTicket->id;
            $eventCategoryTicketPriceIds[] = $event_category_ticket_prices_id;


        }
        if(count($eventCategoryTicketPriceIds) == 2){
            $eventCategoryTicketPriceIds1 = $eventCategoryTicketPriceIds[0];
            $eventCategoryTicketPriceIds2 = $eventCategoryTicketPriceIds[1];
            $eventCategoryTicketsId1 = $EventCategoryTicketIds[0];
            $eventCategoryTicketsId2 = $EventCategoryTicketIds[1];
            $eventCategoryIds1 = $eventCategoryIds[0];
            $eventCategoryIds2 = $eventCategoryIds[1];
        } else {
            $eventCategoryTicketPriceIds1 = $eventCategoryTicketPriceIds[0];
            $eventCategoryTicketPriceIds2 = null;
            $eventCategoryTicketsId1 = $EventCategoryTicketIds[0];
            $eventCategoryTicketsId2 = null;
            $eventCategoryIds1 = $eventCategoryIds[0];
            $eventCategoryIds2 = null;

        }
        // Output or further process the fetched data


        ///
        $txnid = StringHelper::generateUniqueRandomString(18, 'txnid', EventRegistrationHold::class);
        $referenceid = StringHelper::generateUniqueRandomString(18, 'referenceid', EventRegistrationHold::class);
        $remarks = 'Event Registration (Event ID:' . $event->id . ')';

        $paymentDetails = $this->fetchPaymentDetails($event_id, $event_category_ticket_prices_ids);


        $ticketDetails = $this->fetchTicketDetails($event_id, $event_category_ticket_prices_ids);

            if (isset($ticketDetails['event']['categories'])) {
                foreach ($ticketDetails['event']['categories'] as $category => $tickets) {
                    foreach ($tickets as $ticket) {
                        // Extract and store ticket names
                        $ticketNames[] = $ticket['ticket'];
                    }
                }
            }

            $ticketNamesString = implode(', ', $ticketNames);
            if (strlen($ticketNamesString) > 50) {
                $ticketNamesString = substr($ticketNamesString, 0, 50) . '...';
            }
        $particulars = $ticketNamesString;
        $priceSum = array_sum(array_column($paymentDetails, 'price'));

        $txnamt = $priceSum * 100;
        $combainedDetails = [
            'event_title' => $event->name,
            'nmc_registration_number' => $nmc_registration_number,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'email_address' => $email_address,
            'phone_number' => $phone_number,
            'txnamt' => $txnamt / 100,
            'payment_method' => $payment_method,
            'ticket_details' => $ticketDetails
        ];

        $paymentDetailsJson = json_encode($combainedDetails, JSON_PRETTY_PRINT);


        $string = "MERCHANTID=$merchantid,APPID=$appid,APPNAME=$appname,TXNID=$txnid,TXNDATE=$currentDate,TXNCRNCY=NPR,TXNAMT=$txnamt,REFERENCEID=$referenceid,REMARKS=$remarks,PARTICULARS=$particulars,TOKEN=TOKEN";
        $token = $this->eventService->generateHash($string);

        $data = [
            'event_name' => $event->name,
            'event_id' => $event_id,
            'nmc_registration_number' => $nmc_registration_number,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'email_address' => $email_address,
            'phone_number' => $phone_number,
            'merchantid' => $merchantid,
            'appid' => $appid,
            'appname' => $appname,
            'txnid' => $txnid,
            'currentDate' => $currentDate,
            'txnamt' => $txnamt,
            'referenceid' => $referenceid,
            'particulars' => $particulars,
            'token' => $token,
            'payment_method' => $payment_method,
            'event_category_ticket_prices_ids' => $payment_data['event_category_ticket_prices_ids'],
            'payment_details' => $paymentDetailsJson,
            'payment_method' => $payment_method,
            'total_amount' => $txnamt,
            'remarks' => $remarks,
            'event_category_id' => $eventCategoryIds1,
            'event_category_ticket_id' =>$eventCategoryTicketsId1,
            'event_category_ticket_price_id' => $eventCategoryTicketPriceIds1,
            'event_category_id_two' => $eventCategoryIds2,
            'event_category_ticket_id_two' =>$eventCategoryTicketsId2,
            'event_category_ticket_price_id_two' => $eventCategoryTicketPriceIds2,
            'status' => 'REQUESTED',

        ];

        return view('preview', compact('data', 'tickets', 'paymentDetails'));
    }

    public function success(Request $request)
    {

        $txnid = $request->query('TXNID');
        $hold = EventRegistrationHold::where('txnid', $txnid)->first();
        if($hold){

        $responseValidation = $this->connectIpsService->getPaymentValidation($hold->txnid,$hold->txnamt);
        if($responseValidation['status'] !== 'SUCCESS') {


        if($responseValidation['status'] === 'FAILED'){
            $hold->status = 'FAILED';

        }
        if($responseValidation['status'] === 'ERROR'){
            $hold->status = 'ERROR';

        }
        $hold->save();
    }

        $responseTransaction = $this->connectIpsService->getTransactionDetail($hold->txnid,$hold->txnamt);


        $responseTransaction = $this->connectIpsService->getTransactionDetail($hold->txnid,$hold->txnamt);
             // Log transaction details to the transaction log
             Log::channel('transaction')->info('Transaction Details', [
                'txnid' => $hold->txnid,
                'txnamt' => $hold->txnamt,
                'response' => $responseTransaction,
            ]);

            // Also log general application info
            Log::channel('transaction')->info('Checked payment status for transaction.', [
                'txnid' => $hold->txnid,
                'txnamt' => $hold->txnamt,
                'response' => $responseValidation,
            ]);
        if($responseTransaction['status'] === 'SUCCESS') {
            $copyRecordResponse = RecordHelper::copyRecord($hold->id);

        $hold->status = 'SUCCESS';
        $hold->save();

        Log::channel('transaction')->info('Record copy response: ' . json_encode($copyRecordResponse));

        }
    }
        // Set a success message in the session
        session()->flash('success', "Transaction has been successfully completed.");
        // Clear the session data
        session()->forget('data');
        // Return the success view
        return view('success');
    }


    public function fail(Request $request)
    {
        // Fetch the transaction ID from the request query parameters
       $txnid = $request->query('TXNID');

       // Optionally, fetch the hold record based on the transaction ID
       $hold = EventRegistrationHold::where('txnid', $txnid)->first();
       if($hold){

        $responseValidation = $this->connectIpsService->getPaymentValidation($hold->txnid,$hold->txnamt);
        if($responseValidation['status'] === 'ERROR'){
            $hold->status = 'ERROR';

        }
        if($responseValidation['status'] === 'FAILED'){
            $hold->status = 'FAILED';

        }
        $hold->save();
        Log::channel('transaction')->info('Checked payment status for transaction.', [
            'txnid' => $hold->txnid,
            'txnamt' => $hold->txnamt,
            'response' => $responseValidation,
        ]);

       }

        session()->flash('error', "Transaction has been terminated.");

        // Return the success view
        return view('fail');
    }

    private function fetchPaymentDetails($event_id, $event_category_ticket_prices_ids)
    {

        $paymentDetails = EventCategoryTicketPrice::join('event_category_tickets', 'event_category_ticket_prices.event_category_ticket_id', '=', 'event_category_tickets.id')
            ->join('event_categories', 'event_category_tickets.event_category_id', '=', 'event_categories.id')
            ->join('events', 'event_categories.event_id', '=', 'events.id')
            ->where('events.id', $event_id)
            ->whereIn('event_category_ticket_prices.id', $event_category_ticket_prices_ids)
            ->select(
                'event_categories.title as category_title',
                'event_category_tickets.id as ticket_id',
                'event_category_tickets.title',
                'event_category_tickets.location',
                'event_category_tickets.start_date',
                'event_category_tickets.end_date',
                'event_category_ticket_prices.price',
                'event_category_ticket_prices.id as ticket_price_id',
                'event_category_ticket_prices.event_category_ticket_name as event_category_ticket_name'
            )
            ->orderBy('event_categories.id', 'asc') // Order by start date in ascending order
            ->orderBy('event_category_tickets.id', 'asc') // Order by price in descending order
            ->get()
            ->map(function ($item) {
                return [
                    'category_title' => $item->category_title,
                    'ticket_id' => $item->ticket_id,
                    'title' => $item->title,
                    'location' => $item->location,
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'price' => $item->price,
                    'event_category_ticket_name' => $item->event_category_ticket_name,
                ];
            })
            ->toArray();

        return $paymentDetails;
    }
    private function fetchTicketDetails($event_id, $event_category_ticket_prices_ids)
    {
        $paymentDetails = EventCategoryTicketPrice::join('event_category_tickets', 'event_category_ticket_prices.event_category_ticket_id', '=', 'event_category_tickets.id')
            ->join('event_categories', 'event_category_tickets.event_category_id', '=', 'event_categories.id')
            ->join('events', 'event_categories.event_id', '=', 'events.id')
            ->where('events.id', $event_id)
            ->whereIn('event_category_ticket_prices.id', $event_category_ticket_prices_ids)
            ->select(
                'events.name as event_name',
                'events.location as event_location',
                'event_categories.title as category_title',
                'event_category_tickets.title as ticket_title',
                'event_category_ticket_prices.price'
            )
            ->orderBy('event_categories.id', 'asc')  // Order by category ID in ascending order
            ->orderBy('event_category_tickets.id', 'asc')  // Order by ticket ID in ascending order
            ->get()
            ->groupBy('event_name')
            ->mapWithKeys(function ($eventGroup, $eventName) {
                return [
                    'event' => [
                        'title' => $eventGroup->first()->event_name,
                        'location' => $eventGroup->first()->event_location,
                        'categories' => $eventGroup->groupBy('category_title')->map(function ($categoryGroup) {
                            return $categoryGroup->map(function ($item) {
                                return [
                                    'ticket' => $item->ticket_title,
                                    'price' => $item->price,
                                ];
                            })->sortBy('price')->values();  // Sort tickets by price
                        })->toArray()
                    ]
                ];
            })
            ->toArray();


        return $paymentDetails;
    }

    public function combinePaymentDetails($validatedData, $eventTitle, $paymentDetails)
    {
        return [
            'nmc_registration_number' => $validatedData['nmc_registration_number'],
            'first_name' => $validatedData['first_name'],
            'middle_name' => $validatedData['middle_name'],
            'last_name' => $validatedData['last_name'],
            'email_address' => $validatedData['email_address'],
            'phone_number' => $validatedData['phone_number'],
            'event_title' => $eventTitle,
            'payment_details' => $paymentDetails,
        ];
    }
}
