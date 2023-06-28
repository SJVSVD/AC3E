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
        Schema::create('CTB_detalleRendicion', function (Blueprint $table) {
            $table->id();
            $table->integer('idRendicion');
            $table->foreign("idRendicion")->references("id")->on("CTB_rendicion")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idTipoDocumento');
            $table->foreign("idTipoDocumento")->references("id")->on("CTB_tipoDocumento")->onDelete("cascade")->onUpdate("cascade");
            $table->date('fecha');
            $table->integer('nroDocumento')->nullable();
            $table->string('rut')->nullable();
            $table->integer('idTipoGasto');
            $table->foreign("idTipoGasto")->references("id")->on("CTB_tipoGasto")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idGasto');
            $table->foreign("idGasto")->references("id")->on("CTB_gasto")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('monto');
            $table->longText('foto');
            $table->string('observaciones');
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
        Schema::dropIfExists('CTB_detalleRendicion');
        Schema::table('CTB_detalleRendicion', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
