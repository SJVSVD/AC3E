<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_facturas', function (Blueprint $table) {
            $table->id();
            $table->string('idFactura')->unique();
            $table->string('identificador');
            $table->string('estado');
            $table->string('rutProveedor');
            $table->string('folio');
            $table->integer('tipoDocumento');
            $table->string('tipoCompra');
            $table->string('razonSocial');
            $table->date('fechaDcto');
            $table->date('fechaRecepcion')->nullable();
            $table->date('fechaAcuse')->nullable();
            $table->integer('montoExento')->nullable();
            $table->integer('montoNeto');
            $table->integer('montoIVARec');
            $table->integer('montoIVANoRec');
            $table->integer('montoTotal');
            $table->integer('codigoIVANoRec')->nullable();
            $table->integer('montoNetoAF')->nullable();
            $table->integer('IVAAF')->nullable();
            $table->integer('IVAUsoComun')->nullable();
            $table->integer('impSinCred')->nullable();
            $table->integer('IVANoRetenido')->nullable();
            $table->integer('NCEoNDE')->nullable();
            $table->integer('otroImpuesto')->nullable();
            $table->integer('valorOtroImpuesto')->nullable();
            $table->integer('tasaOtroImpuesto')->nullable();
            $table->integer('idEmpresa');
            $table->foreign('idEmpresa')->references('id')->on('SYS_empresas')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizable')->nullable();
            $table->integer('idAutorizador1')->nullable();
            $table->foreign('idAutorizador1')->references('id')->on('SYS_autorizadores')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizadoAuth1')->nullable();
            $table->integer('idAutorizador2')->nullable();
            $table->foreign('idAutorizador2')->references('id')->on('SYS_autorizadores')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizadoAuth2')->nullable();
            $table->string('observaciones', 400)->nullable();
            $table->string('objeciones', 400)->nullable();
            $table->date('fechaReq')->nullable();
            $table->boolean('autorizado')->nullable();
            $table->boolean('gastoAutorizado')->nullable();
            $table->boolean('pagoAutorizado')->nullable();
            $table->date('fechaAuthGasto')->nullable();
            $table->date('fechaAuthPago')->nullable();
            $table->integer('idUsuarioSolicitante');
            $table->foreign('idUsuarioSolicitante')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('url', 300)->nullable();
            $table->timestamps();
            // $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_facturas');
        // Schema::table('CTB_facturas', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
    }
};
