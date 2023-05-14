<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('strategy_name');
            $table->boolean('enabled');
            $table->integer('trade_quantity');
            $table->integer('number_of_trades');
            $table->integer('running_counts');
            $table->decimal('max_stock_price');
            $table->integer('max_stops_allowed');
            $table->boolean('change_quantity_after_stops');
            $table->integer('quantity_after_stop');
            $table->decimal('stop_price');
            $table->decimal('limit_price');
            $table->decimal('limit_price_offset');
            $table->decimal('high_price_buffer');
            $table->decimal('profit');
            $table->json('symbols');
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
        Schema::dropIfExists('strategies');
    }
}
