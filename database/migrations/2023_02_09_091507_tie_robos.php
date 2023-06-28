<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('TIE_robos', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->integer('idTipoRobo');
            $table->foreign("idTipoRobo")->references("id")->on("SYS_tipoRobo")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idTienda');
            $table->foreign("idTienda")->references("id")->on("SYS_sucursales")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->date('fecha');
            $table->time('hora');
            $table->string('observacion', 400)->nullable();
            $table->integer('idSupervisor')->nullable();
            $table->foreign("idSupervisor")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('montoRobado');
            $table->boolean('autorizado')->nullable();
            $table->date('fechaAuth')->nullable();
            $table->string('observacionAutorizador', 400)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('TIE_robos');
        Schema::table('TIE_robos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
