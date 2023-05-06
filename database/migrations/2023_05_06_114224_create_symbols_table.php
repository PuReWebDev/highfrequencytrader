<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbols', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('AssetType');
            $table->string('Name');
            $table->text('Description');
            $table->integer('CIK');
            $table->string('Exchange');
            $table->string('Currency');
            $table->string('Country');
            $table->string('Sector');
            $table->string('Industry');
            $table->string('Address');
            $table->string('FiscalYearEnd');
            $table->date('LatestQuarter');
            $table->bigInteger('MarketCapitalization');
            $table->bigInteger('EBITDA');
            $table->float('PERatio');
            $table->float('PEGRatio');
            $table->double('BookValue');
            $table->string('DividendPerShare');
            $table->string('DividendYield');
            $table->string('EPS');
            $table->string('RevenuePerShareTTM');
            $table->float('ProfitMargin');
            $table->float('OperatingMarginTTM');
            $table->float('ReturnOnAssetsTTM');
            $table->string('ReturnOnEquityTTM');
            $table->string('RevenueTTM');
            $table->string('GrossProfitTTM');
            $table->decimal('DilutedEPSTTM');
            $table->float('QuarterlyEarningsGrowthYOY');
            $table->float('QuarterlyRevenueGrowthYOY');
            $table->decimal('AnalystTargetPrice');
            $table->decimal('TrailingPE');
            $table->decimal('ForwardPE');
            $table->double('PriceToSalesRatioTTM');
            $table->decimal('PriceToBookRatio');
            $table->decimal('EVToRevenue');
            $table->decimal('EVToEBITDA');
            $table->float('Beta');
            $table->decimal('52WeekHigh');
            $table->decimal('52WeekLow');
            $table->decimal('50DayMovingAverage');
            $table->decimal('200DayMovingAverage');
            $table->bigInteger('SharesOutstanding');
            $table->string('DividendDate');
            $table->string('ExDividendDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('symbols');
    }
}
