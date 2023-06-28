<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_kam', function (Blueprint $table) {
            $table->id();
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idEmpresa');
            $table->foreign("idEmpresa")->references("id")->on("SYS_empresas")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_kam');
        Schema::table('SYS_kam', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
