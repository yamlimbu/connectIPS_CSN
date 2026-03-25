<?php

namespace App\Console\Commands;

use App\Models\EventRegistrationHold;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PaymentStatusNotification;
use GuzzleHttp\Client;
use App\Http\Controllers\Api\ConnectIPSGatewayController;
use Illuminate\Http\Request;
use App\Services\ConnectIpsService;
use App\Helpers\RecordHelper;
use Illuminate\Support\Facades\Log;
class CheckPaymentStatus extends Command
{
    protected $signature = 'payment:check-status';
    protected $description = 'Check the payment status for pending transactions';
    protected $connectIPSGatewayController;

    public function __construct(ConnectIPSGatewayController $connectIPSGatewayController, ConnectIpsService $connectIpsService)
    {
        parent::__construct();
        $this->connectIPSGatewayController = $connectIPSGatewayController;
        $this->connectIpsService = $connectIpsService;

    }

    public function handle()
    {
        // Retrieve pending transactions
        $paymentRequesteds = EventRegistrationHold::where('status', 'REQUESTED')->get();
        if ($paymentRequesteds->isNotEmpty()) {

        foreach ($paymentRequesteds as $hold) {
            // Instantiate the PaymentController

            $responseValidation = $this->connectIpsService->getPaymentValidation($hold->txnid,$hold->txnamt);
            if($responseValidation['status'] !== 'SUCCESS') {
                    if($responseValidation['status'] === 'FAILED'){
                        $hold->status = 'FAILED';

                    }
                    if($responseValidation['status'] === 'ERROR' && $responseValidation['statusDesc'] === 'TRANSACTION NOT FOUND'){
                        $hold->status = 'NOT_FOUND';
                        $status = 'not_found';
                    }
                    if($responseValidation['status'] === 'ERROR' && $responseValidation['statusDesc'] === 'TRANSACTION INCOMPLETE'){
                        $hold->status = 'INCOMPLETE';
                        $status = 'incomplete';
                    }
                    $hold->save();
                }
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
            $this->info('Payment status checked and notifications sent.');
        }
    } else {

        $this->info('No pending(REQUESTED) payements left in Registration Holds.');

    }
    }
}

