<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'banner',
        'banner_text',
        'start_date',
        'end_date',
        'location',
        'information',
        'application_id',
        'created_by',
        'updated_by',
        'is_featured',
        'is_active'

    ];

    protected $hidden = [];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date'   => 'datetime:Y-m-d',
    ];

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('UTC');
    }

public function getEndDateAttribute($value)
{
    return Carbon::parse($value)->setTimezone('UTC');
}

    public function eventSessions()
    {
        return $this->hasMany(EventSession::class);
    }

    public function eventCategories()
    {
        return $this->hasMany(EventCategory::class);
    }

    // Convert date to UTC format before saving
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->setTimezone('UTC')->format('Y-m-d');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->setTimezone('UTC')->format('Y-m-d');
    }
}
