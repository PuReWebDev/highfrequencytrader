<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('accountId')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('code');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('token_type')->nullable();
            $table->integer('expires_in')->nullable();
            $table->integer('refresh_token_expires_in')->nullable();
            $table->text('scope')->nullable();
            $table->timestamp('last_used_at')->nullable();
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
        Schema::dropIfExists('tokens');
    }
}
