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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigIncrements('account_id');
            $table->string('order_id');
            $table->string('symbol');
            $table->string('cusip');
            $table->string('session');
            $table->string('duration');
            $table->decimal('price');
            $table->decimal('expected_profit');
            $table->decimal('actual_profit')->nullable();
            $table->integer('quantity');
            $table->integer('filled_quantity')->nullable();
            $table->integer('remaining_quantity');
            $table->decimal('stop_price')->nullable();
            $table->string('stop_price_link_basis')->nullable();
            $table->string('stop_price_link_type')->nullable();
            $table->string('stop_price_offset')->nullable();
            $table->string('stop_type')->nullable();
            $table->string('order_duration')->nullable();;
            $table->date('cancel_time')->nullable();;
            $table->dateTime('entered_time');
            $table->dateTime('close_time')->nullable();;
            $table->decimal('trailing_amount')->nullable();
            $table->string('status');
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
