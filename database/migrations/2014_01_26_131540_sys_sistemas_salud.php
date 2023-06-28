<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_sistemasSalud', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('porcentaje');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_sistemasSalud');
        Schema::table('SYS_sistemasSalud', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
