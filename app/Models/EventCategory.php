<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EventCategory extends Model
{
    use HasFactory;

    protected $table = 'event_categories';

     protected $fillable = [
        'event_id',
        'title',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id')->withDefault();
    }

    public function getCreatedBy()
    {
        return $this->createdBy->getFullName();
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy->getFullName();
    }
    public function tickets()
    {
        return $this->hasMany(EventCategoryTicket::class, 'event_category_id');
    }

}
