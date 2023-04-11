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
            $table->string('accountId');
            $table->double('shortQuantity')->nullable();
            $table->double('averagePrice')->nullable();
            $table->double('currentDayProfitLoss')->nullable();
            $table->double('currentDayProfitLossPercentage')->nullable();
            $table->double('longQuantity')->nullable();
            $table->double('settledLongQuantity')->nullable();
            $table->double('settledShortQuantity')->nullable();
            $table->double('agedQuantity')->nullable();
            $table->string('assetType')->nullable();
            $table->string('cusip')->nullable();
            $table->string('symbol')->nullable();
            $table->string('description')->nullable();
            $table->double('marketValue')->nullable();
            $table->double('maintenanceRequirement')->nullable();
            $table->double('previousSessionLongQuantity')->nullable();
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
