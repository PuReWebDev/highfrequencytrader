<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'symbols';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'symbol',
        'AssetType',
        'Name',
        'Description',
        'CIK',
        'Exchange',
        'Currency',
        'Country',
        'Sector',
        'Industry',
        'Address',
        'FiscalYearEnd',
        'LatestQuarter',
        'MarketCapitalization',
        'EBITDA',
        'PERatio',
        'PEGRatio',
        'BookValue',
        'DividendPerShare',
        'DividendYield',
        'EPS',
        'RevenuePerShareTTM',
        'ProfitMargin',
        'OperatingMarginTTM',
        'ReturnOnAssetsTTM',
        'ReturnOnEquityTTM',
        'RevenueTTM',
        'GrossProfitTTM',
        'DilutedEPSTTM',
        'QuarterlyEarningsGrowthYOY',
        'QuarterlyRevenueGrowthYOY',
        'AnalystTargetPrice',
        'TrailingPE',
        'ForwardPE',
        'PriceToSalesRatioTTM',
        'PriceToBookRatio',
        'EVToRevenue',
        'EVToEBITDA',
        'Beta',
        '52WeekHigh',
        '52WeekLow',
        '50DayMovingAverage',
        '200DayMovingAverage',
        'SharesOutstanding',
        'DividendDate',
        'ExDividendDate',
    ];
}
