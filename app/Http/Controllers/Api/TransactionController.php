<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TransactionRequestLog;
use Illuminate\Support\Facades\Log;
Use App\Services\TransactionService;
use App\Models\EventRegistrationHold;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function storeTransaction(Request $request)
    {

        $clientDetails = $this->transactionService->getClientDetails($request);
        $allData = $request->all();

       $details = [
            'merchantid' => $allData['merchantid'],
            'appid' => $allData['appid'],
            'appname' =>  $allData['appname'],
            'txnid' => $allData['txnid'],
            'txndate' => $allData['txndate'],
            'txncrncy' => $allData['txncrncy'],
            'txnamt' => $allData['txnamt'],
            'referenceid' => $allData['referenceid'],
            'remarks' => $allData['remarks'],
            'particulars' => $allData['particulars'],
            'token' => $allData['token']
        ];


        TransactionRequestLog::create([
            'details' => json_encode($details),
            'agent' => json_encode($clientDetails)
        ]);

        $hold = EventRegistrationHold::create([
            'event_id' => $allData['event_id'],
            'nmc_registration_number' =>  $allData['nmc_registration_number'],
            // 'first_name' =>  $allData['first_name'],
            // 'last_name' =>  $allData['last_name'],
            // 'middle_name' =>  $allData['middle_name'],
            'full_name' =>  $allData['full_name'],
            'address' =>  $allData['address'],
            'gender' =>  $allData['gender'],
            'degree' =>  $allData['degree'],
            'email_address' =>  $allData['email_address'],
            'phone_number' =>  $allData['phone_number'],
            'payment_details' =>  $allData['payment_details'],
            'payment_method' =>  $allData['payment_method'],
            'total_amount' =>  $allData['total_amount'],
            'status' =>  $allData['status'],
            'retry_attempts' => 0,
            'event_category_id' =>  $allData['event_category_id'],
            'event_category_ticket_id' =>  $allData['event_category_ticket_id'],
            'event_category_ticket_price_id' =>  $allData['event_category_ticket_price_id'],
            'event_category_id_two' =>  $allData['event_category_id_two'],
            'event_category_ticket_id_two' =>  $allData['event_category_ticket_id_two'],
            'event_category_ticket_price_id_two' =>  $allData['event_category_ticket_price_id_two'],
            'txnid'=> $allData['txnid'],
            'txndate' => Carbon::now()->format('Y-m-d'),
            'txncrncy' => $allData['txncrncy'],
            'txnamt'=> $allData['txnamt'],
            'referenceid'=> $allData['referenceid'],
            'remarks'=> $allData['remarks'],
            'particulars'=> $allData['particulars'],
            'token'=> $allData['token'],
            'ip_address' => $clientDetails['ip_address'],
            'device'=> $clientDetails['device'],
            'platform'=> $clientDetails['platform'],
            'browser'=> $clientDetails['browser'],
            'browser_version'=> $clientDetails['browser_version'],
            'is_mobile' => $clientDetails['is_mobile']?? false,
            'is_tablet' => $clientDetails['is_tablet']?? false,
            'is_desktop' => $clientDetails['is_desktop']?? false,
            'is_bot' => $clientDetails['is_bot']?? false,
            'is_iphone' => $clientDetails['is_iphone']?? false,
            'is_android' => $clientDetails['is_android']?? false,

            ]);


        return response()->json(['message' => 'Transaction logged successfully']);
    }

    public function getTransactionDetails(){

    }
}
