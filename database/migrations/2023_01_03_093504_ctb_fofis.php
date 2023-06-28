<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_fondosFijos', function (Blueprint $table) {
            $table->id();
            $table->integer('folioFofi');
            $table->date('fecha');
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('observacion', 400)->nullable();
            $table->integer('cantidadGastos');
            $table->integer('fofiAsignado');
            $table->integer('montoTotal');
            $table->string('estado');
            $table->integer('idAutorizador')->nullable();
            $table->foreign('idAutorizador')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('autorizado')->nullable();
            $table->date('fechaAuth')->nullable();
            $table->string('objeciones', 400)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_fondosFijos');
        Schema::table('CTB_fondosFijos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
