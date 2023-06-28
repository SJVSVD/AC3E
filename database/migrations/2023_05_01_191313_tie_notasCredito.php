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
        Schema::create('TIE_notasCreditos', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->date('fecha');
            $table->integer('idTienda');
            $table->foreign("idTienda")->references("id")->on("SYS_sucursales")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('notaCredito')->unique();
            $table->integer('boleta');
            $table->integer('montoNotaCredito');
            $table->integer('montoBoleta');
            $table->integer('cantidadArtNCV');
            $table->integer('cantidadArtBoleta');
            $table->string('metodoPago');
            $table->string('rutCliente');
            $table->string('nomCliente');
            $table->string('motivo')->nullable();
            $table->boolean('credito');             
            $table->longText('imagen')->nullable();
            $table->integer('idAutorizadorPago')->nullable();
            $table->foreign('idAutorizadorPago')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizadoPago')->nullable();
            $table->date('fechaAuthPago')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('TIE_notasCreditos');
        Schema::table('TIE_notasCreditos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
