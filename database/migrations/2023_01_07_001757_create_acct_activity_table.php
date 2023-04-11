<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acct_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('accountId');
            $table->timestamp('event_timestamp');
            $table->string('activity_type');
            $table->string('activity_description');
            $table->string('activity_description_text');
            $table->string('symbol');
            $table->json('fundamentals');
            $table->integer('quantity');
            $table->float('price', 10, 2);
            $table->float('commission', 10, 2);
            $table->float('total_commission', 10, 2);
            $table->string('commission_currency');
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
        Schema::dropIfExists('acct_activities');
    }
}
