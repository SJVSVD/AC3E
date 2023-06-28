<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ETC_comisiones', function (Blueprint $table) {
            $table->id();
            $table->integer('idUsuario')->nullable();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->boolean('estado');
            $table->string('rut');
            $table->string('nombre');
            $table->string('correo');
            $table->string('telefono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ETC_comisiones');
        Schema::table('ETC_comisiones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
