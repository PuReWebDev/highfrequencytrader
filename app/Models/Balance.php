<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accountId',
        'balanceType',
        'user_id',
        'accruedInterest',
        'availableFunds',
        'availableFundsNonMarginableTrade',
        'bondValue',
        'buyingPower',
        'buyingPowerNonMarginableTrade',
        'cashBalance',
        'cashAvailableForTrading',
        'cashReceipts',
        'dayTradingBuyingPower',
        'dayTradingBuyingPowerCall',
        'dayTradingEquityCall',
        'equity',
        'equityPercentage',
        'liquidationValue',
        'longMarginValue',
        'longOptionMarketValue',
        'longStockValue',
        'maintenanceCall',
        'maintenanceRequirement',
        'margin',
        'marginEquity',
        'moneyMarketFund',
        'mutualFundValue',
        'regTCall',
        'shortMarginValue',
        'shortOptionMarketValue',
        'shortStockValue',
        'totalCash',
        'isInCall',
        'pendingDeposits',
        'marginBalance',
        'shortBalance',
        'accountValue',
        'savings',
        'sma',
        'shortMarketValue',
        'pendingDeposits',
        'mutualFundValue',
        'stockBuyingPower',
    ];

    /**
     * Get the user that owns the Balance
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The account that this balance belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
