<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('idFactura')->unsigned();
            $table->foreign('idFactura')->references('idFactura')->on('CTB_facturas')->onDelete('cascade')->onUpdate('cascade');
            $table->date('fechaPago');
            $table->integer('montoPagado');
            $table->string('usuarioPago');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_pagos');
        Schema::table('CTB_pagos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
