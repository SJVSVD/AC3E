<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_zonas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->integer('idPais');
            $table->foreign("idPais")->references("id")->on("SYS_paises")->onDelete("cascade")->onUpdate("cascade");
            $table->string('idPaisZona')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_zonas');
        Schema::table('SYS_zonas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
