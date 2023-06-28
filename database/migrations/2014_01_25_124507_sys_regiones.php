<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_regiones', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('abreviatura')->nullable();
            $table->string('capital');
            $table->integer('idPais');
            $table->foreign("idPais")->references("id")->on("SYS_paises")->onDelete("cascade")->onUpdate("cascade");
            $table->string('idPaisRegion')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('SYS_regiones');
        Schema::table('SYS_regiones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
