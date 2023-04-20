<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accountId',
        'user_id',
        'orderId',
        'symbol',
        'instruction',
        'positionEffect',
        'orderStrategyType',
        'assetType',
        'cusip',
        'session',
        'duration',
        'cancelable',
        'editable',
        'price',
        'expectedProfit',
        'actualProfit',
        'quantity',
        'filledQuantity',
        'remainingQuantity',
        'stopPrice',
        'stopPriceLinkBasis',
        'stopPriceLinkType',
        'session',
        'stopPriceOffset',
        'stopType',
        'orderDuration',
        'orderLegType',
        'legId',
        'cancelTime',
        'enteredTime',
        'closeTime',
        'trailingAmount',
        'status',
        'statusDescription',
        'tag',
        'filledQuantity',
        'remainingQuantity',
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
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

