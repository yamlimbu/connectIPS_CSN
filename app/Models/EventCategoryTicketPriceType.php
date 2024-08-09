<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategoryTicketPriceType extends Model
{
    use HasFactory;

    protected $table = 'event_category_ticket_price_types';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [];
    // Other model properties and methods
    public function eventCategoryTicketPrices()
{
    return $this->hasMany(EventCategoryTicketPrice::class, 'event_category_ticket_price_types_id');
}

}
