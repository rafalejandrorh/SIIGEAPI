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
        Schema::create('dependencias_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_dependencias');
            $table->unsignedBigInteger('id_servicios');
            $table->timestamps();

            $table->foreign('id_dependencias')->references('id')->on('dependencias'); 
            $table->foreign('id_servicios')->references('id')->on('servicios'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencias_servicios');
    }
};
