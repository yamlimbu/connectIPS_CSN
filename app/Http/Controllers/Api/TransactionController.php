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

        // Retrieve the record by its hold_id
        $hold = EventRegistrationHold::find($allData['hold_id']);

        if ($hold) {
            // Update the existing record
            $hold->update([
                'status' => 'REQUESTED',

            ]);
        }

        return response()->json(['message' => 'Transaction logged successfully']);
    }

    public function getTransactionDetails(){

    }
}
