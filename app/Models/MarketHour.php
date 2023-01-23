<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketHour extends Model
{
    protected $fillable = [
        'market', 'is_open', 'date'
    ];
}
