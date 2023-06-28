<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('TIE_roboDetalles', function (Blueprint $table) {
            $table->id();
            $table->integer('idRobo');
            $table->foreign("idRobo")->references("id")->on("TIE_robos")->onDelete("cascade")->onUpdate("cascade");
            $table->string('item');
            $table->string('descripcion');
            $table->integer('cantidad');
            $table->integer('monto');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('TIE_roboDetalles');
        Schema::table('TIE_roboDetalles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
