<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('description');
            $table->double('bidPrice');
            $table->bigInteger('bidSize');
            $table->string('bidId');
            $table->double('askPrice');
            $table->bigInteger('askSize');
            $table->string('askId');
            $table->double('lastPrice');
            $table->bigInteger('lastSize');
            $table->string('lastId');
            $table->double('openPrice');
            $table->double('highPrice');
            $table->double('lowPrice');
            $table->double('closePrice');
            $table->double('netChange');
            $table->bigInteger('totalVolume');
            $table->bigInteger('quoteTimeInLong');
            $table->bigInteger('tradeTimeInLong');
            $table->double('mark');
            $table->string('exchange');
            $table->string('exchangeName');
            $table->boolean('marginable')->default(false);
            $table->boolean('shortable')->default(false);
            $table->double('volatility');
            $table->bigInteger('digits');
            $table->double('52WkHigh');
            $table->double('52WkLow');
            $table->double('peRatio');
            $table->double('divAmount');
            $table->double('divYield');
            $table->string('divDate');
            $table->string('securityStatus');
            $table->double('regularMarketLastPrice');
            $table->bigInteger('regularMarketLastSize');
            $table->double('regularMarketNetChange');
            $table->bigInteger('regularMarketTradeTimeInLong');
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
        Schema::dropIfExists('quotes');
    }
}
