<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_token',
        'hold_id',
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
        'ip_address',
        'device',
        'platform',
        'browser',
        'txnid',
        'txndate',
        'txncrncy',
        'txnamt',
        'referenceid',
        'remarks',
        'particulars',
        'token',
        'event_category_id',
        'event_category_ticket_id',
        'event_category_ticket_price_id',
        'event_category_id_two',
        'event_category_ticket_id_two',
        'event_category_ticket_price_id_two',
        'browser_version',
        'is_mobile',
        'is_tablet',
        'is_desktop',
        'is_bot',
        'is_iphone',
        'is_android',
        'profession',
        'current_working_institution',
        'full_name',
        'address',
        'degree',
        'gender',
        'event_category_ticket_prices_ids'
    ];
    protected $casts = [
        'payment_details' => 'array', // Cast to array for'
        'event_category_ticket_prices_ids' => 'array', // Cast to array for'

    ];
    /**
     * Get the event associated with the registration.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            $registration->event_token = \Str::random(10); // Generate a unique API token
        });
    }
    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }
    public function eventRegistrationHold()
    {
        return $this->belongsTo(EventRegistrationHold::class, 'hold_id');
    }
}
