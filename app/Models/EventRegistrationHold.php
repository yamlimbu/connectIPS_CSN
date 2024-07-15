<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistrationHold extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'nmc_registration_number',
        'first_name',
        'middle_name',
        'last_name',
        'email_address',
        'phone_number',
        'payment_details',
        'payment_method',
        'total_amount',
        'status',
        'payment_token',
        'retry_attempts'
    ];
    protected $casts = [
        'payment_details' => 'array', // Cast to array for JSONB

    ];
    /**
     * Get the event associated with the registration.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
