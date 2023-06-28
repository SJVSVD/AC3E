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
        Schema::create('TIE_detallesPagoNCV', function (Blueprint $table) {
            $table->id();
            $table->integer('idNCV');
            $table->foreign("idNCV")->references("id")->on("TIE_notasCreditos")->onDelete("cascade")->onUpdate("cascade");
            $table->string('tipoPago');
            $table->string('banco')->nullable();
            $table->string('tipoCuenta')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('rut')->nullable();
            
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
        Schema::dropIfExists('TIE_detallesPagoNCV');
        Schema::table('TIE_detallesPagoNCV', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
