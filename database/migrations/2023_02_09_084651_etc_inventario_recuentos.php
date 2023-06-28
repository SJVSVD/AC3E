<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('ETC_inventarioRecuentos', function (Blueprint $table) {
            $table->id();
            $table->integer('idInventario');
            $table->string('producto');
            $table->string('almacen');
            $table->integer('cantidadAlmacen');
            $table->integer('cantidadContada');
            $table->integer('desviacion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ETC_inventarioRecuentos');
        Schema::table('ETC_inventarioRecuentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
