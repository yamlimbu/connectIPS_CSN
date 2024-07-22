<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Event\Models\EventCategory;

class EventCategoryTicket  extends Model
{
    use HasFactory;

    protected $table = 'event_category_tickets';

    protected $fillable = [
        'event_category_id',
        'title',
        'location',
        'start_date',
        'end_date',
        'information',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function eventcategory()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id')->withDefault();
    }

    public function getCreatedBy()
    {
        return $this->createdBy->getFullName();
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy->getFullName();
    }

    public function prices()
    {
        return $this->hasMany(EventCategoryTicketPrice::class, 'event_category_ticket_id');
    }

}
