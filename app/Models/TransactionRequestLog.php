<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRequestLog extends Model
{
    protected $fillable = ['details', 'agent', 'status'];
    protected $casts = [
        'details' => 'array',
        'agent' => 'array',
    ];
}
