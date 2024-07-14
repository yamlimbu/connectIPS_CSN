<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\EventRegisterRequest;
use App\Services\ApiService;
use Illuminate\Support\Facades\Storage;

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
        // Fetch data from the API
        $response = $this->apiService->get('/event/1/tickets');


        // Decode the JSON response
        $data = $response->json('data');

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


    function event_register()
    {

        // if ($request->registration_form == 'submitted') {

        $merchantid = config('app.merchantid');
        $appid = config('app.appid');
        $appname = 'Cardiac-Society';
        $abc = config('app.CONNECT_IPS_BASEURL');
        $string = "MERCHANTID=$merchantid,APPID=$appid,APPNAME=$appname,TXNID=txn-1233,TXNDATE=15-03-2022,TXNCRNCY=NPR,TXNAMT=500,REFERENCEID=REF-001,REMARKS=RMKS-001,PARTICULARS=PART-001,TOKEN=TOKEN";

        $data = [
            // 'event_id' => $request->input('event_id'),
            // 'nmc_registration_number' => $request->input('nmc_registration_number'),
            // 'first_name' => $request->input('first_name'),
            // 'middle_name' => $request->input('middle_name'),
            // 'last_name' => $request->input('last_name'),
            // 'email_address' => $request->input('email_address'),
            // 'phone_number' => $request->input('phone_number'),
            // // 'event_category_ticket_ids' => $request->input('event_category_ticket_ids'),
            // 'event_category_ticket_ids[0]' => 1,
            // 'event_category_ticket_ids[1]' => 3,
            // 'payment_method' => $request->input('payment_method'),
            // 'payment_receipt' => $request->input('payment_receipt'),
            // 'payment_status' => $request->input('payment_status'),

            // 'total_amount' => $request->input('total_amount'),
            // 'transaction_id' => $request->input('transaction_id'),
            'token' => $this->generateHash($string)

        ];


        return view('event_register_preview', compact('data'));
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

    public function processPaymentSuccess()
    {
        echo 'Success';
    }

    public function processPaymentFail()
    {
        echo 'Fail';
    }
}
