<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_colaboradores', function (Blueprint $table) {
            $table->id();
            $table->string('identificador');
            $table->string('nombre1');
            $table->string('nombre2');
            $table->string('apellido1');
            $table->string('apellido2');
            $table->integer('idNacionalidad');
            $table->foreign('idNacionalidad')->references('id')->on('SYS_nacionalidades')->onDelete('cascade')->onUpdate('cascade');
            $table->date('fechaNac');
            $table->integer('idPais');
            $table->foreign('idPais')->references('id')->on('SYS_paises')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('idRegion');
            $table->foreign('idRegion')->references('id')->on('SYS_regiones')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('idComuna');
            $table->foreign('idComuna')->references('id')->on('SYS_comunas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('sector');
            $table->string('direccion');
            $table->string('correoPersonal')->nullable();
            $table->string('correoEmpresa')->unique();
            $table->string('telefonoPersonal')->nullable();
            $table->string('telefonoEmpresa');
            $table->integer('idEstadoCivil');
            $table->foreign('idEstadoCivil')->references('id')->on('SYS_estadosCiviles')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('idDepartamento');
            $table->foreign('idDepartamento')->references('id')->on('SYS_departamentos')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('idArea');
            $table->foreign('idArea')->references('id')->on('SYS_areas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('cargo');
            $table->integer('idSistemaSalud');
            $table->foreign('idSistemaSalud')->references('id')->on('SYS_sistemasSalud')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('idAfpAsociada');
            $table->foreign('idAfpAsociada')->references('id')->on('SYS_afp')->onDelete('cascade')->onUpdate('cascade');
            $table->string('sobreMi',300)->nullable();
            $table->boolean('estado')->default(1);
            $table->text('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_colaboradores');
    }
};
