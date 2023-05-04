<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movers', function (Blueprint $table) {
            $table->id();
            $table->double('change');
            $table->string('description');
            $table->string('direction');
            $table->double('last');
            $table->string('symbol');
            $table->bigInteger('totalVolume');
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
        Schema::dropIfExists('movers');
    }
}
