<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_comunas', function (Blueprint $table) {
            $table->id();
            $table->string('comuna');
            $table->integer('idRegion');
            $table->foreign("idRegion")->references("id")->on("SYS_regiones")->onDelete("cascade")->onUpdate("cascade");
            $table->string('idRegionComuna')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down()
    {
        Schema::dropIfExists('SYS_comunas');
        Schema::table('SYS_comunas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
