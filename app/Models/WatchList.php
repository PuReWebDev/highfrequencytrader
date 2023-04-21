<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchList extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'watch_lists';

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
