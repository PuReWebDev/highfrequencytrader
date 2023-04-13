<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketHour extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'market_hours';

    protected $fillable = [
        'market', 'date', 'start', 'end'
    ];
}
