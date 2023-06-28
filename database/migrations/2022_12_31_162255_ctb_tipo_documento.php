<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_tipoDocumento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('detalle');
            $table->boolean('rut')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_tipoDocumento');
        Schema::table('CTB_tipoDocumento', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
