<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('account_id');
            $table->double('shortQuantity');
            $table->double('averagePrice');
            $table->double('currentDayProfitLoss');
            $table->double('currentDayProfitLossPercentage');
            $table->double('longQuantity');
            $table->double('settledLongQuantity');
            $table->double('settledShortQuantity');
            $table->double('agedQuantity');
            $table->string('assetType');
            $table->string('cusip');
            $table->string('symbol');
            $table->string('description');
            $table->double('marketValue');
            $table->double('maintenanceRequirement');
            $table->double('previousSessionLongQuantity');
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
        Schema::dropIfExists('positions');
    }
}
