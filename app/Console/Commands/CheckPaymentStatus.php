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

        foreach ($paymentRequesteds as $paymentRequested) {
            // Instantiate the PaymentController

            $responseValidation = $this->connectIpsService->getPaymentValidation($paymentRequested->txnid,$paymentRequested->txnamt);

            if($responseValidation['status'] !== 'SUCCESS') {
                    if($responseValidation['status'] === 'FAILED'){
                        $paymentRequested->status = 'FAILED';

                    }
                    if($responseValidation['status'] === 'ERROR'){
                        $paymentRequested->status = 'ERROR';

                    }
                    $paymentRequested->save();
                }
            $responseTransaction = $this->connectIpsService->getTransactionDetail($paymentRequested->txnid,$paymentRequested->txnamt);
             // Log transaction details to the transaction log
             Log::channel('transaction')->info('Transaction Details', [
                'txnid' => $paymentRequested->txnid,
                'txnamt' => $paymentRequested->txnamt,
                'response' => $responseTransaction,
            ]);

            // Also log general application info
            Log::channel('transaction')->info('Checked payment status for transaction.', [
                'txnid' => $paymentRequested->txnid,
                'txnamt' => $paymentRequested->txnamt,
                'response' => $responseValidation,
            ]);
            if($responseTransaction['status'] === 'SUCCESS') {

              $copyRecordResponse = RecordHelper::copyRecord($paymentRequested->id);
                $paymentRequested->status = 'SUCCESS';
                $paymentRequested->save();
                Log::channel('transaction')->info('Record copy response: ' . json_encode($copyRecordResponse));


            }
            $this->info('Payment status checked and notifications sent.');
        }
    } else {

        $this->info('No pending(REQUESTED) payements left in Registration Holds.');

    }
    }
}

