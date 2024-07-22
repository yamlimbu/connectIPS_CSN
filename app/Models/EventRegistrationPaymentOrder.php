<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistrationPaymentOrder extends Model
{
    use HasFactory;

    protected $table = 'event_registration_payment_orders';

    protected $fillable = [
        'status',
        'statusDesc',
        'merchantId',
        'appId',
        'referenceId',
        'txnAmt',
        'token',
        'debitBankCode',
        'txnId',
        'batchId',
        'txnDate',
        'txnCrncy',
        'chargeAmt',
        'chargeLiability',
        'refId',
        'remarks',
        'particulars',
        'creditStatus',
    ];

    public $timestamps = true;
}
