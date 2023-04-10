<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'user_id',
        'account_id',
        'shortQuantity',
        'averagePrice',
        'currentDayProfitLoss',
        'currentDayProfitLossPercentage',
        'longQuantity',
        'settledLongQuantity',
        'settledShortQuantity',
        'agedQuantity',
        'assetType',
        'cusip',
        'symbol',
        'description',
        'marketValue',
        'maintenanceRequirement',
        'previousSessionLongQuantity',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
