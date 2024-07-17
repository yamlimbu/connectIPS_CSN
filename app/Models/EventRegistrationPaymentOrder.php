<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistrationPaymentOrder extends Model
{
    protected $fillable = [
        'event_registration_id',
        'payment_details',
        'payment_method',
        'payment_receipt',
        'payment_status',
        'total_amount',
        'transaction_id',
        'payment_date'
    ];
    protected $casts = [
        'payment_details' => 'array', // Cast to array for JSONB
        'payment_date' => 'date',

    ];
    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }
}
