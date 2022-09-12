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
        Schema::create('token_historial', function (Blueprint $table) {
            $table->id();
            $table->integer('id_dependencias');
            $table->string('token');
            $table->string('fecha_creacion_token');
            $table->string('fecha_expiracion_token');
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
        Schema::dropIfExists('token_historial');
    }
};
