<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quotes';

    protected $fillable = [
        'symbol',
        'description',
        'bidPrice',
        'bidSize',
        'bidId',
        'askPrice',
        'askSize',
        'askId',
        'lastPrice',
        'lastSize',
        'lastId',
        'openPrice',
        'highPrice',
        'lowPrice',
        'closePrice',
        'netChange',
        'totalVolume',
        'quoteTimeInLong',
        'tradeTimeInLong',
        'mark',
        'exchange',
        'exchangeName',
        'marginable',
        'shortable',
        'volatility',
        'digits',
        '52WkHigh',
        '52WkLow',
        'peRatio',
        'divAmount',
        'divYield',
        'securityStatus',
        'regularMarketLastPrice',
        'regularMarketLastSize',
        'regularMarketNetChange',
        'regularMarketTradeTimeInLong',
    ];
}
