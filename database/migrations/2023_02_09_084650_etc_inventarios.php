<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ETC_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->integer('idTipoInv');
            $table->foreign("idTipoInv")->references("id")->on("SYS_tipoInv")->onDelete("cascade")->onUpdate("cascade");
            $table->string('superFamilia');
            $table->integer('idSucursal');
            $table->foreign("idSucursal")->references("id")->on("SYS_sucursales")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idSupervisor');
            $table->foreign("idSupervisor")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idPromotor');
            $table->foreign("idPromotor")->references("id")->on("SYS_promotores")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idComision');
            $table->foreign("idComision")->references("id")->on("ETC_comisiones")->onDelete("cascade")->onUpdate("cascade");
            $table->date('fecha');
            $table->time('hora');
            $table->integer('recuento')->nullable();
            $table->integer('inventario')->nullable();
            $table->integer('contabilizacion')->nullable();
            $table->integer('diferencia')->nullable();
            $table->string('observacion')->nullable();
            $table->boolean('autorizadoSupervisora')->nullable();
            $table->string('observacionesSupervisora', 400)->nullable();
            $table->date('fechaAutSup')->nullable();
            $table->boolean('autorizadoComision')->nullable();
            $table->string('observacionesComision', 400)->nullable();
            $table->date('fechaAutCom')->nullable();
            $table->date('fechaFinalizacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ETC_inventarios');
        Schema::table('ETC_inventarios', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
