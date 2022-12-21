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
        Schema::create('trazas_api', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('fecha_request');
            $table->string('request');
            $table->string('response');
            $table->string('ip');
            $table->string('mac');
            $table->string('usuario');
            $table->string('ente');
            $table->string('token');
            $table->string('dependencia');
            $table->string('organismo');
            $table->string('ministerio');
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
        Schema::dropIfExists('trazas_api');
    }
};
