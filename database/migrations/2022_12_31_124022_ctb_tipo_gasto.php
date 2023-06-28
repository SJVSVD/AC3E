<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_tipoGasto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('detalle');
            $table->integer('cuentaContable');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('CTB_tipoGasto');
        Schema::table('CTB_tipoGasto', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
