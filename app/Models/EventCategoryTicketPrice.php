<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventCategoryTicketPrice  extends Model
{
    use HasFactory;

    protected $table = 'event_category_ticket_prices';

    protected $fillable = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'event_category_ticket_id',
        'offer_price_end_date',
        'price',
        'event_category_ticket_price_types_id',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'offer_price_end_date' => 'datetime',
        'price' => 'decimal:2',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function eventcategoryticket()
    {
        return $this->belongsTo(EventCategoryTicket::class, 'event_category_ticket_id')->withDefault();
    }

    public function getCreatedBy()
    {
        return $this->createdBy->getFullName();
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy->getFullName();
    }
        // Convert dates to UTC format for display
        public function getOfferPriceStartDateAttribute($value)
        {
            return Carbon::parse($value)->setTimezone('UTC');
        }

        public function getOfferPriceEndDateAttribute($value)
        {
            return Carbon::parse($value)->setTimezone('UTC');
        }



    public function setOfferPriceEndDateAttribute($value)
    {
        $this->attributes['offer_price_end_date'] = Carbon::parse($value)->setTimezone('UTC')->toDateTimeString();
    }
    public function eventCategoryTicketPriceType()
    {
        return $this->belongsTo(EventCategoryTicketPriceType::class, 'event_category_ticket_price_types_id');
    }
}
