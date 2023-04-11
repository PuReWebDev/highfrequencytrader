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
            $table->string('accountId');
            $table->string('balanceType');
            $table->double('accruedInterest')->nullable();
            $table->double('availableFunds')->nullable();
            $table->double('availableFundsNonMarginableTrade')->nullable();
            $table->double('bondValue')->nullable();
            $table->double('buyingPower')->nullable();
            $table->double('buyingPowerNonMarginableTrade')->nullable();
            $table->double('cashBalance')->nullable();
            $table->double('cashAvailableForTrading')->nullable();
            $table->double('cashReceipts')->nullable();
            $table->double('dayTradingBuyingPower')->nullable();
            $table->double('dayTradingBuyingPowerCall')->nullable();
            $table->double('dayTradingEquityCall')->nullable();
            $table->double('equity')->nullable();
            $table->double('equityPercentage')->nullable();
            $table->double('liquidationValue')->nullable();
            $table->double('longMarginValue')->nullable();
            $table->double('longOptionMarketValue')->nullable();
            $table->double('longStockValue')->nullable();
            $table->double('maintenanceCall')->nullable();
            $table->double('maintenanceRequirement')->nullable();
            $table->double('margin')->nullable();
            $table->double('marginEquity')->nullable();
            $table->double('moneyMarketFund')->nullable();
            $table->double('mutualFundValue')->nullable();
            $table->double('regTCall')->nullable();
            $table->double('shortMarginValue')->nullable();
            $table->double('shortOptionMarketValue')->nullable();
            $table->double('shortStockValue')->nullable();
            $table->double('totalCash')->nullable();
            $table->double('isInCall')->nullable();
            $table->double('pendingDeposits')->nullable();
            $table->double('marginBalance')->nullable();
            $table->double('shortBalance')->nullable();
            $table->double('accountValue')->nullable();
            $table->double('savings')->nullable();
            $table->double('sma')->nullable();
            $table->double('shortMarketValue')->nullable();
            $table->double('stockBuyingPower')->nullable();
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
