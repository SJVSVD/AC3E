<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TIE_metasDiarias', function (Blueprint $table) {
            $table->id();
            $table->integer('idTienda');
            $table->foreign("idTienda")->references("id")->on("SYS_sucursales")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('meta_total');
            $table->integer('meta_carteras')->nullable();
            $table->integer('meta_marroquineria')->nullable();
            $table->integer('meta_calzado')->nullable();
            $table->integer('meta_perfume')->nullable();
            $table->integer('meta_vestimenta')->nullable();
            $table->integer('año');
            $table->integer('mes');
            $table->integer('dia');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TIE_metasDiarias');
        Schema::table('TIE_metasDiarias', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
