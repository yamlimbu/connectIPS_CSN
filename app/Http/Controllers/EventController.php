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
use App\Services\EventService;
use App\Http\Controllers\Api\ConnectIPSGatewayController;
use App\Services\ConnectIpsService;
use App\Helpers\RecordHelper;
use Illuminate\Support\Facades\Log;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Crypt;

class EventController extends Controller
{
    protected $apiService;
    protected $connectIpsService;
    protected $connectIPSGatewayController;

    public function __construct(
        ApiService $apiService,
        EventService $eventService,
        ConnectIpsService $connectIpsService,
        ConnectIPSGatewayController $connectIPSGatewayController,
        TransactionService $transactionService
    ) {
        $this->apiService = $apiService;
        $this->eventService = $eventService;
        $this->connectIpsService = $connectIpsService;
        $this->connectIPSGatewayController = $connectIPSGatewayController;
        $this->transactionService = $transactionService;
    }


    public function index()
    {

        $events = null;
        // Pass data to the view

        $event_id = 1;
        $encryptedId = Crypt::encrypt($event_id);

        return view('welcome', compact('events', 'encryptedId'));
    }


    public function register(Request $request)
    {
        $eventRegistrationHold = null;
        if($request->query('hold_id')){
        $hold_id = Crypt::decrypt($request->query('hold_id'));

        $eventRegistrationHold = EventRegistrationHold::where('id', $hold_id)->first();
        }
        session()->forget('data');

        $event_id = Crypt::decrypt($request->event_id);
        $encryptedId = Crypt::encrypt($event_id);

        // Fetch data from DB
        $data = Event::where('is_active', true)
        ->with([
            'eventCategories' => function ($query) {
                $query->orderBy('id')->with([
                    'tickets' => function ($query) {
                        $query->orderBy('id')->with([
                            'prices' => function ($query) {
                                $query->join('event_category_ticket_price_types', 'event_category_ticket_prices.event_category_ticket_price_types_id', '=', 'event_category_ticket_price_types.id')
                                    ->orderBy('event_category_ticket_price_types.order', 'asc') // Ordering by the 'order' column in event_category_ticket_price_types table
                                    ->orderBy('price', 'asc') // Optional: Further ordering by price
                                    ->select('event_category_ticket_prices.*', 'event_category_ticket_price_types.order as price_type_order'); // Select required columns and include 'order' if needed
                            }
                        ]);
                    }
                ]);
            }
        ])
        ->findOrFail($event_id);

        // Pass data to the view
        return view('register', compact('data', 'event_id', 'eventRegistrationHold', 'encryptedId'));
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
            $validatedData['event_id'] = $request->event_id;
            if ($request->hold_id) {
                $validatedData['hold_id'] = $request->hold_id;
            }

            //return redirect(route('preview'));
            $request->session()->put('data', $validatedData);
            return redirect()->route('preview');
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
        $event_id = Crypt::decrypt($payment_data['event_id']);

        // Check if $paymentData is not empty
        if (empty($payment_data)) {
            return redirect()->route('event.register', ['event_id' => $event_id])->withErrors('No payment data found for preview.');
        }
        $nmc_registration_number = $payment_data['nmc_registration_number'];
        // $first_name = $payment_data['first_name'];
        // $middle_name = $payment_data['middle_name'];
        // $last_name = $payment_data['last_name'];
        $full_name = $payment_data['full_name'];
        $address = $payment_data['address'];
        $degree = $payment_data['degree'];
        $gender = $payment_data['gender'];
        $email_address = $payment_data['email_address'];
        $phone_number = $payment_data['phone_number'];

        $payment_method = 'connectIPS';
        $currentDate = Carbon::now()->format('d-m-Y');
        $event = Event::find($event_id);

        $event_category_ticket_prices_ids = array_map('intval', $payment_data['event_category_ticket_prices_ids']);

        $eventCategoryTicketPriceIds = [];
        $eventCategoryIds = [];
        $EventCategoryTicketIds = [];

        foreach ($event_category_ticket_prices_ids as $event_category_ticket_prices_id) {
            // Fetch the event category ticket price based on $id
            $eventCategoryTicketPrice = EventCategoryTicketPrice::find($event_category_ticket_prices_id);
            $EventCategoryTicket = EventCategoryTicket::find($eventCategoryTicketPrice->event_category_ticket_id);
            $eventCategoryIds[] = $EventCategoryTicket->event_category_id;
            $EventCategoryTicketIds[] = $EventCategoryTicket->id;
            $eventCategoryTicketPriceIds[] = $event_category_ticket_prices_id;
        }
        if (count($eventCategoryTicketPriceIds) == 2) {
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
            // 'first_name' => $first_name,
            // 'middle_name' => $middle_name,
            // 'last_name' => $last_name,
            'full_name' => $full_name,
            'address' => $address,
            'degree' => $degree,
            'gender' => $gender,
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
            // 'first_name' => $first_name,
            // 'middle_name' => $middle_name,
            // 'last_name' => $last_name,
            'full_name' => $full_name,
            'address' => $address,
            'degree' => $degree,
            'gender' => $gender,
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
            'event_category_ticket_id' => $eventCategoryTicketsId1,
            'event_category_ticket_price_id' => $eventCategoryTicketPriceIds1,
            'event_category_id_two' => $eventCategoryIds2,
            'event_category_ticket_id_two' => $eventCategoryTicketsId2,
            'event_category_ticket_price_id_two' => $eventCategoryTicketPriceIds2,
            'status' => 'PENDING',

        ];
        $clientDetails = $this->transactionService->getClientDetails($request);
        //insert in hold table
        if (isset($payment_data['hold_id'])) {
            $hold_id = Crypt::decrypt($payment_data['hold_id']);
            $hold = EventRegistrationHold::find($hold_id);
            if ($hold) {
                // Update the existing record
                $hold->update([
                    'event_id' => $event_id,
                    'nmc_registration_number' => $nmc_registration_number,
                    'full_name' => $full_name,
                    'address' => $address,
                    'gender' => $gender,
                    'degree' => $degree,
                    'email_address' => $email_address,
                    'phone_number' => $phone_number,
                    'payment_details' => $paymentDetailsJson,
                    'payment_method' => $payment_method,
                    'total_amount' => $txnamt,
                    'status' => 'PENDING',
                    'retry_attempts' => 0,
                    'event_category_id' => $eventCategoryIds1,
                    'event_category_ticket_id' => $eventCategoryTicketsId1,
                    'event_category_ticket_price_id' => $eventCategoryTicketPriceIds1,
                    'event_category_id_two' => $eventCategoryIds2,
                    'event_category_ticket_id_two' => $eventCategoryTicketsId2,
                    'event_category_ticket_price_id_two' => $eventCategoryTicketPriceIds2,
                    'txnid' => $txnid,
                    'txndate' => Carbon::now()->format('Y-m-d'),
                    'txncrncy' => $txncrncy,
                    'txnamt' => $txnamt,
                    'referenceid' => $referenceid,
                    'remarks' => $remarks,
                    'particulars' => $particulars,
                    'token' => $token,
                    'ip_address' => $clientDetails['ip_address'],
                    'device' => $clientDetails['device'],
                    'platform' => $clientDetails['platform'],
                    'browser' => $clientDetails['browser'],
                    'browser_version' => $clientDetails['browser_version'],
                    'is_mobile' => $clientDetails['is_mobile'] ?? false,
                    'is_tablet' => $clientDetails['is_tablet'] ?? false,
                    'is_desktop' => $clientDetails['is_desktop'] ?? false,
                    'is_bot' => $clientDetails['is_bot'] ?? false,
                    'is_iphone' => $clientDetails['is_iphone'] ?? false,
                    'is_android' => $clientDetails['is_android'] ?? false,
                    'event_category_ticket_prices_ids' => $event_category_ticket_prices_ids,
                ]);
            }
        } else {
            $hold = EventRegistrationHold::create([
                'event_id' => $event_id,
                'nmc_registration_number' =>  $nmc_registration_number,
                // 'first_name' =>  $allData['first_name'],
                // 'last_name' =>  $allData['last_name'],
                // 'middle_name' =>  $allData['middle_name'],
                'full_name' =>  $full_name,
                'address' =>  $address,
                'gender' =>  $gender,
                'degree' =>  $degree,
                'email_address' =>  $email_address,
                'phone_number' =>  $phone_number,
                'payment_details' =>  $paymentDetailsJson,
                'payment_method' =>  $payment_method,
                'total_amount' =>  $txnamt,
                'status' =>  'PENDING',
                'retry_attempts' => 0,
                'event_category_id' =>  $eventCategoryIds1,
                'event_category_ticket_id' =>  $eventCategoryTicketsId1,
                'event_category_ticket_price_id' =>  $eventCategoryTicketPriceIds1,
                'event_category_id_two' =>  $eventCategoryIds2,
                'event_category_ticket_id_two' =>  $eventCategoryTicketsId2,
                'event_category_ticket_price_id_two' =>  $eventCategoryTicketPriceIds2,
                'txnid' => $txnid,
                'txndate' => Carbon::now()->format('Y-m-d'),
                'txncrncy' => $txncrncy,
                'txnamt' => $txnamt,
                'referenceid' => $referenceid,
                'remarks' => $remarks,
                'particulars' => $particulars,
                'token' => $token,
                'ip_address' => $clientDetails['ip_address'],
                'device' => $clientDetails['device'],
                'platform' => $clientDetails['platform'],
                'browser' => $clientDetails['browser'],
                'browser_version' => $clientDetails['browser_version'],
                'is_mobile' => $clientDetails['is_mobile'] ?? false,
                'is_tablet' => $clientDetails['is_tablet'] ?? false,
                'is_desktop' => $clientDetails['is_desktop'] ?? false,
                'is_bot' => $clientDetails['is_bot'] ?? false,
                'is_iphone' => $clientDetails['is_iphone'] ?? false,
                'is_android' => $clientDetails['is_android'] ?? false,
                'event_category_ticket_prices_ids' => $event_category_ticket_prices_ids,

            ]);
        }
        return view('preview', compact('data', 'tickets', 'paymentDetails', 'hold'));
    }

    public function success(Request $request)
    {

        $txnid = $request->query('TXNID');
        $hold = EventRegistrationHold::where('txnid', $txnid)->first();
        if ($hold) {

            $responseValidation = $this->connectIpsService->getPaymentValidation($hold->txnid, $hold->txnamt);
            if ($responseValidation['status'] !== 'SUCCESS') {


                if ($responseValidation['status'] === 'FAILED') {
                    $hold->status = 'FAILED';
                }
                if ($responseValidation['status'] === 'ERROR' && $responseValidation['statusDesc'] === 'TRANSACTION NOT FOUND') {
                    $hold->status = 'NOT_FOUND';
                    $status = 'not_found';
                }
                if ($responseValidation['status'] === 'ERROR' && $responseValidation['statusDesc'] === 'TRANSACTION INCOMPLETE') {
                    $hold->status = 'INCOMPLETE';
                    $status = 'incomplete';
                }
                $hold->save();
            }
            $responseTransaction = $this->connectIpsService->getTransactionDetail($hold->txnid, $hold->txnamt);
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
            if ($responseTransaction['status'] === 'SUCCESS') {
                $copyRecordResponse = RecordHelper::copyRecord($hold->id);

                $hold->status = 'SUCCESS';
                $hold->save();

                Log::channel('transaction')->info('Record copy response: ' . json_encode($copyRecordResponse));
                $registration = EventRegistration::where('event_token', $copyRecordResponse['event_token'])->first();

            }
        }
        // Set a success message in the session
        session()->flash('success', "Transaction has been successfully completed.");
        // Clear the session data
        session()->forget('data');
        // Return the success view
        $isMobile = (bool) $hold->is_mobile;
        $event_category_ticket_prices_ids = ($registration->event_category_ticket_prices_ids);
        $ticketDerails = EventCategoryTicketPrice::with(['eventcategoryticket.eventcategory.event'])
            ->find($event_category_ticket_prices_ids);

        $event = Event::find($registration->event_id);
        $encryptedId = Crypt::encrypt($registration->event_id);

        return view('success', compact('isMobile', 'registration', 'ticketDerails', 'event', 'encryptedId'));
    }


    public function fail(Request $request)
    {
        // Fetch the transaction ID from the request query parameters
        $txnid = $request->query('TXNID');

        // Optionally, fetch the hold record based on the transaction ID
        $hold = EventRegistrationHold::where('txnid', $txnid)->first();
        if ($hold) {

            $responseValidation = $this->connectIpsService->getPaymentValidation($hold->txnid, $hold->txnamt);
            // if($responseValidation['status'] === 'ERROR'){
            //     $hold->status = 'ERROR';

            // }
            if ($responseValidation['status'] === 'FAILED') {
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
        $isMobile = $hold !== null ? (bool) $hold->is_mobile : false;
        $encryptedId = Crypt::encrypt($hold->event_id);

        // Return the success view
        return view('fail', compact('isMobile', 'encryptedId'));
    }

    private function fetchPaymentDetails($event_id, $event_category_ticket_prices_ids)
    {
        $paymentDetails = EventCategoryTicketPrice::join('event_category_tickets', 'event_category_ticket_prices.event_category_ticket_id', '=', 'event_category_tickets.id')
            ->join('event_categories', 'event_category_tickets.event_category_id', '=', 'event_categories.id')
            ->join('events', 'event_categories.event_id', '=', 'events.id')
            ->join('event_category_ticket_price_types', 'event_category_ticket_prices.event_category_ticket_price_types_id', '=', 'event_category_ticket_price_types.id')
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
                'event_category_ticket_price_types.name as price_type'  // Include price type
            )
            ->orderBy('event_categories.id', 'asc')  // Order by category ID in ascending order
            ->orderBy('event_category_tickets.id', 'asc')  // Order by ticket ID in ascending order
            ->orderBy('event_category_ticket_prices.price', 'desc')  // Order by price in descending order
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
                    'price_type' => $item->price_type,  // Include price type
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
        ->join('event_category_ticket_price_types', 'event_category_ticket_prices.event_category_ticket_price_types_id', '=', 'event_category_ticket_price_types.id')
        ->where('events.id', $event_id)
        ->whereIn('event_category_ticket_prices.id', $event_category_ticket_prices_ids)
        ->select(
            'events.name as event_name',
            'events.location as event_location',
            'event_categories.title as category_title',
            'event_category_tickets.title as ticket_title',
            'event_category_ticket_prices.price',
            'event_category_ticket_price_types.name as price_type'  // Include price type in the selection
        )
        ->orderBy('event_categories.id', 'asc')  // Order by category ID
        ->orderBy('event_category_tickets.id', 'asc')  // Order by ticket ID
        ->get()
        ->groupBy('event_name')  // Group by event name
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
                                'price_type' => $item->price_type,  // Include price type in the result
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

    public function searchNmc(Request $request)
    {
        $query = $request->input('query');

        // Load the JSON file from the public disk
        $jsonContent = Storage::disk('public')->get('nmc/doctors_details.json');
        $data = json_decode($jsonContent, true);

        // Filter the data based on the query
        $results = array_filter($data, function ($item) use ($query) {
            return stripos($item['nmc_no'], $query) !== false;
        });

        // Return the results as JSON
        return response()->json(array_values($results));
    }

    public function eventRegistrationDetails(Request $request)
    {
        $registration = EventRegistration::where('event_token', $request->event_token)->first();

        $event_category_ticket_prices_ids = ($registration->event_category_ticket_prices_ids);
        $ticketDerails = EventCategoryTicketPrice::with(['eventcategoryticket.eventcategory.event'])
            ->find($event_category_ticket_prices_ids);

        $event = Event::find($registration->event_id);
        return view('gatepass-detail', compact('registration','event', 'ticketDerails'));

    }
}
