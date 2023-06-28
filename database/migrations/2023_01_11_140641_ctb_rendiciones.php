<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_rendicion', function (Blueprint $table) {
            $table->id();
            $table->integer('folio');
            $table->string('colaborador');
            $table->integer('montoSoli');
            $table->integer('montoRendi')->nullable();
            $table->integer('idUsuarioSoli');
            $table->foreign("idUsuarioSoli")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idUsuarioRendi')->nullable();
            $table->foreign("idUsuarioRendi")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->date('fechaSoli');
            $table->date('fechaRendi')->nullable();
            $table->string('detalleSoli');
            $table->string('detalleRendi')->nullable();
            $table->integer('idTipoMoneda');
            $table->foreign("idTipoMoneda")->references("id")->on("SYS_monedas")->onDelete("cascade")->onUpdate("cascade");
            $table->string('estado');
            $table->boolean('pendiente')->default(1);
            $table->integer('idAutorizadorRendi')->nullable();
            $table->foreign('idAutorizadorRendi')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizadoRendi')->nullable();
            $table->date('fechaAuthRendi')->nullable();
            $table->string('objecionesRendi', 400)->nullable();
            $table->integer('idAutorizadorPago')->nullable();
            $table->foreign('idAutorizadorPago')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizadoPago')->nullable();
            $table->date('fechaAuthPago')->nullable();
            $table->string('objecionesPago', 400)->nullable();
            $table->string('tipoImposicion')->nullable();           
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_rendicion');
        Schema::table('CTB_rendicion', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
