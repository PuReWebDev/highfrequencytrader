<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category')->nullable();
            $table->string('date');
            $table->string('exchange');
            $table->boolean('is_open');
            $table->string('market_type');
            $table->string('product')->nullable();
            $table->string('product_name')->nullable();
            $table->boolean('is_open')->default(false);
            $table->string('session_hours');
            $table->dateTime('date');
            $table->dateTime('open_time');
            $table->dateTime('close_time');
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
        Schema::dropIfExists('market_hours');
    }
}
