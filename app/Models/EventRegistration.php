<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
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
        'event_token',
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
}
