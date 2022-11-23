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
        Schema::create('traza_users_siipol', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_accion');
            $table->string('valores_modificados');
            $table->timestamps();

            $table->foreign('id_accion')->references('id')->on('traza_acciones'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traza_users_siipol');
    }
};
