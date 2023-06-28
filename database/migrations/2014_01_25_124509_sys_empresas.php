<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_empresas', function (Blueprint $table) {
            $table->id();
            $table->string('rut')->unique();
            $table->string('razonSocial');
            $table->string('giro');
            $table->string('direccion');
            $table->integer('idPais');
            $table->foreign("idPais")->references("id")->on("SYS_paises")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idRegion');
            $table->foreign("idRegion")->references("id")->on("SYS_regiones")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idComuna');
            $table->foreign("idComuna")->references("id")->on("SYS_comunas")->onDelete("cascade")->onUpdate("cascade");
            $table->string('telefono');
            $table->string('fax');
            $table->string('codigoPostal');
            $table->boolean('tipo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_empresas');
        Schema::table('SYS_empresas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
