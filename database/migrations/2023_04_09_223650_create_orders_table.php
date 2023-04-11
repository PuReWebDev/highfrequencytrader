<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('accountId');
            $table->string('orderId');
            $table->string('symbol');
            $table->string('instruction');
            $table->string('positionEffect');
            $table->string('orderStrategyType')->nullable();
            $table->string('assetType');
            $table->string('cusip')->nullable();
            $table->string('session');
            $table->string('duration')->nullable();
            $table->string('cancelable')->nullable();
            $table->string('editable')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('expectedProfit')->nullable();
            $table->decimal('actualProfit')->nullable();
            $table->integer('quantity');
            $table->integer('filledQuantity')->nullable();
            $table->integer('remainingQuantity')->nullable();
            $table->decimal('stopPrice')->nullable();
            $table->string('stopPriceLinkBasis')->nullable();
            $table->string('stopPriceLinkType')->nullable();
            $table->string('stopPriceOffset')->nullable();
            $table->string('stopType')->nullable();
            $table->string('orderDuration')->nullable();;
            $table->string('orderLegType')->nullable();;
            $table->string('legId')->nullable();;
            $table->date('cancelTime')->nullable();;
            $table->dateTime('enteredTime')->nullable();
            $table->dateTime('closeTime')->nullable();;
            $table->decimal('trailingAmount')->nullable();
            $table->string('status');
            $table->string('tag')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
