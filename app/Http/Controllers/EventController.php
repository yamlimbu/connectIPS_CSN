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

class EventController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
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



    public function preview()
    {
        // Return the success view
        $merchantid = config('app.merchantid');
        $appid = config('app.appid');
        $appname = config('app.appname');
        $txncrncy = config('app.txncrncy');
        $payment_data = session('data');

        // Check if $paymentData is not empty
        if (empty($payment_data)) {
            return redirect()->route('register/1')->withErrors('No payment data found for preview.');
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

        $event_category_ticket_prices_ids =  array_map('intval', $payment_data['event_category_ticket_prices_ids']);

        ///
        $txnid = StringHelper::generateUniqueRandomString(18, 'payment_token', EventRegistrationHold::class);
        $paymentDetails = $this->fetchPaymentDetails($event_id, $event_category_ticket_prices_ids);


        $ticketDetails = $this->fetchTicketDetails($event_id, $event_category_ticket_prices_ids);

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

        $hold = EventRegistrationHold::create([

            'event_id' => $event_id,
            'nmc_registration_number' => $nmc_registration_number,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'email_address' => $email_address,
            'phone_number' => $phone_number,
            'payment_details' => $paymentDetailsJson,
            'payment_method' => $payment_method,
            'payment_token' => $txnid,
            'total_amount' => $txnamt / 100,
            'status' => 'not_submitted',
        ]);

        $string = "MERCHANTID=$merchantid,APPID=$appid,APPNAME=$appname,TXNID=$txnid,TXNDATE=$currentDate,TXNCRNCY=NPR,TXNAMT=$txnamt,REFERENCEID=REF-001,REMARKS=RMKS-001,PARTICULARS=PART-001,TOKEN=TOKEN";

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
            'token' => $this->generateHash($string),
            // 'token' => '',
            'payment_method' => $payment_method,
            'event_category_ticket_prices_ids' => $payment_data['event_category_ticket_prices_ids']
        ];

        return view('preview', compact('data', 'tickets', 'paymentDetails'));
    }

    public function success(Request $request)
    {
        $txnid = $request->query('TXNID');
        $hold = EventRegistrationHold::where('payment_token', $txnid)->first();

        // Check if the hold record was found
        if (!$hold) {

            // If not found, redirect to home page with an error message
            return redirect('/')->with('status', 'Event registration hold record not found.');
        }
        $this->moveDataToEventRegistration($txnid);

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
        $hold = EventRegistrationHold::where('payment_token', $txnid)->first();

        // Check if the hold record exists
        if ($hold) {
            // Delete the hold record to clean up stale data
            $hold->delete();
        }
        // Set a success message in the session

        session()->flash('error', "Transaction has been terminated.");

        // Return the success view
        return view('fail');
    }
    function generateHash($string)
    {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $opensslConfPath = env('OPENSSL_CONF');
        putenv("OPENSSL_CONF=$opensslConfPath");

        date_default_timezone_set("Asia/Kathmandu");

        // Try to locate certificate file
        $filePath = 'CREDITOR.pfx';
        $fullPath = storage_path('app/private/' . $filePath);

        if (!Storage::disk('private')->exists($filePath)) {
            echo "Error: Unable to read the cert file at path: $filePath\n";
            echo "Full path: $fullPath\n";
        }         // Try to locate certificate file
        $cert_store = Storage::disk('private')->get($filePath);
        // Try to read certificate file
        $password = "123";
        // Try to read the certificate file
        if (openssl_pkcs12_read($cert_store, $cert_info, $password)) {
            if (isset($cert_info['pkey']) && $private_key = openssl_pkey_get_private($cert_info['pkey'])) {
                $array = openssl_pkey_get_details($private_key);
                //print_r($array);  // Print the details of the private key
            } else {
                echo "Error: Unable to extract private key from the certificate store.\n";
            }
        } else {
            echo "Error: Unable to read the cert store. Check the password or file content.\n";
            print_r(error_get_last());
            echo "OpenSSL Error: " . openssl_error_string() . "\n";
        }
        $hash = "";
        if (openssl_sign($string, $signature, $private_key, "sha256WithRSAEncryption")) {
            $hash = base64_encode($signature);
            openssl_free_key($private_key);
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }
        return $hash;
    }
    private function fetchPaymentDetails($event_id, $event_category_ticket_prices_ids)
    {
        //return $this->eventRegistration->fetchPaymentDetails($event_id, $event_category_ticket_ids);

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

    public function moveDataToEventRegistration($txnid)
    {

        try {
            // Start transaction
            DB::beginTransaction();

            // Fetch data from event registration hold
            $hold = EventRegistrationHold::where('payment_token', $txnid)->first();

            // Create a new event registration record
            $registration = EventRegistration::create([
                'event_id' => $hold->event_id,
                'nmc_registration_number' => $hold->nmc_registration_number,
                'first_name' => $hold->first_name,
                'middle_name' => $hold->middle_name,
                'last_name' => $hold->last_name,
                'email_address' => $hold->email_address,
                'phone_number' => $hold->phone_number,
                'payment_token' => $hold->payment_token,
            ]);

            // Fetch event details
            $event = Event::findOrFail($hold->event_id);

            // Prepare validated data
            $validatedData = [
                'nmc_registration_number' => $hold->nmc_registration_number,
                'first_name' => $hold->first_name,
                'middle_name' => $hold->middle_name,
                'last_name' => $hold->last_name,
                'email_address' => $hold->email_address,
                'phone_number' => $hold->phone_number,
            ];


            // Create a new payment order record
            $paymentOrder = EventRegistrationPaymentOrder::create([
                'event_registration_id' => $registration->id,
                'payment_details' => $hold->payment_details, // Store as JSONB
                'payment_method' => $hold->payment_method,
                'payment_receipt' => $hold->payment_receipt, // assuming correct field
                'payment_status' => $hold->payment_status,   // assuming correct field
                'transaction_id' => $txnid,
                'total_amount' => $hold->total_amount,
                'payment_date' => now(),
            ]);

            // Commit transaction
            DB::commit();

            // Optionally delete the hold record
            $hold->delete();
            Mail::to($registration->email_address)->send(new EventCodeMail($registration->event_token));

            return response()->json(['']);
        } catch (Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
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
