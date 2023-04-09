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
        'user_id',
        'order_id',
        'symbol',
        'cusip',
        'session',
        'duration',
        'price',
        'expected_profit',
        'actual_profit',
        'quantity',
        'filled_quantity',
        'remaining_quantity',
        'stop_price',
        'stop_price_link_basis',
        'stop_price_link_type',
        'session',
        'stop_price_offset',
        'stop_type',
        'order_duration',
        'order_leg_collection',
        'cancel_time',
        'entered_time',
        'close_time',
        'trailing_amount',
        'status',
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

