<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'order_id',
        'order_type',
        'order_strategy_type',
        'session',
        'price',
        'stop_price',
        'order_leg_collection',
        'place_time',
        'status',
        'cancel_time',
        'filled_quantity',
        'remaining_quantity',
        'average_price',
        'last_fill_price',
        'last_fill_quantity',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_leg_collection' => 'array',
    ];

    /**
     * The account that this order belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

