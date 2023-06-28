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
        Schema::create('TIE_cierreCajas', function (Blueprint $table) {
            $table->id();
            // Atributos Tienda / Cierre
            $table->integer('idTienda');
            $table->foreign('idTienda')->references('id')->on('SYS_sucursales')->onDelete('cascade')->onUpdate('cascade');
            $table->string('responsableCierre');
            $table->date('diaCierre');
            $table->string('observacionCierre', 400)->nullable();
            $table->longText('imagenVoucher');
            // Valores Importantes
            $table->integer('notaCredito')->nullable();
            $table->integer('ventaBruta')->nullable();
            $table->integer('ventaTotal')->nullable();
            // Valores
            $table->integer('fondoFijo')->nullable();
            $table->integer('idRefFondoFijo')->nullable();
            $table->foreign('idRefFondoFijo')->references('id')->on('CTB_fondosFijos')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('tarjeta')->nullable();
            $table->integer('transferencia')->nullable();
            $table->integer('efectivo')->nullable();
            $table->integer('cheque')->nullable();
            $table->integer('cornershop')->nullable();
            $table->integer('rappi')->nullable();
            $table->integer('personal')->nullable();
            $table->integer('marinaOnline')->nullable();
            $table->integer('vale')->nullable();
            // Depósito
            $table->longText('imagenDeposito')->nullable();
            $table->integer('deposito')->nullable();
            $table->date('fechaDeposito')->nullable();
            $table->string('observacionDeposito', 400)->nullable();
            // Control
            $table->integer('diferenciaDias')->nullable();
            $table->integer('control')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TIE_cierreCajas');
        Schema::table('TIE_cierreCajas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
