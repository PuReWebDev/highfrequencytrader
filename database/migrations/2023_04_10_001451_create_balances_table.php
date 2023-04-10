<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('account_id');
            $table->double('accruedInterest');
            $table->double('availableFunds');
            $table->double('availableFundsNonMarginableTrade');
            $table->double('bondValue');
            $table->double('buyingPower');
            $table->double('buyingPowerNonMarginableTrade');
            $table->double('cashBalance');
            $table->double('cashAvailableForTrading');
            $table->double('cashReceipts');
            $table->double('dayTradingBuyingPower');
            $table->double('dayTradingBuyingPowerCall');
            $table->double('dayTradingEquityCall');
            $table->double('equity');
            $table->double('equityPercentage');
            $table->double('liquidationValue');
            $table->double('longMarginValue');
            $table->double('longOptionMarketValue');
            $table->double('longStockValue');
            $table->double('maintenanceCall');
            $table->double('maintenanceRequirement');
            $table->double('margin');
            $table->double('marginEquity');
            $table->double('moneyMarketFund');
            $table->double('mutualFundValue');
            $table->double('regTCall');
            $table->double('shortMarginValue');
            $table->double('shortOptionMarketValue');
            $table->double('shortStockValue');
            $table->double('totalCash');
            $table->double('isInCall');
            $table->double('pendingDeposits');
            $table->double('marginBalance');
            $table->double('shortBalance');
            $table->double('accountValue');
            $table->double('savings');
            $table->double('sma');
            $table->double('shortMarketValue');
            $table->double('stockBuyingPower');
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
        Schema::dropIfExists('balances');
    }
}
