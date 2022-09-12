<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_organismos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_dependencias')->unique();
            $table->string('token');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('expires_at');

            $table->foreign('id_dependencias')->references('id')->on('dependencias'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token_organismos');
    }
};
