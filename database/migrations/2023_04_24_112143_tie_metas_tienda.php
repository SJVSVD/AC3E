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
        Schema::create('TIE_metasTienda', function (Blueprint $table) {
            $table->id();
            $table->integer('idTienda');
            $table->foreign("idTienda")->references("id")->on("SYS_sucursales")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('meta_total');
            $table->integer('meta_carteras');
            $table->integer('meta_marroquineria');
            $table->integer('meta_calzado');
            $table->integer('meta_perfume');
            $table->integer('meta_vestimenta');
            $table->integer('año');
            $table->integer('mes');
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
        Schema::dropIfExists('TIE_metasTienda');
        Schema::table('TIE_metasTienda', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
