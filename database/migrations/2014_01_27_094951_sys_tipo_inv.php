<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_tipoInv', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('tipo');
            $table->integer('idEmpresaMarca');
            $table->foreign("idEmpresaMarca")->references("id")->on("SYS_empresa_marca")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('SYS_tipoInv');
        Schema::table('SYS_tipoInv', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
