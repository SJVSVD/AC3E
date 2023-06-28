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
        Schema::create('TIE_contadorPersonas', function (Blueprint $table) {
            $table->id();
            $table->integer('idTienda');
            $table->foreign('idTienda')->references('id')->on('SYS_sucursales')->onDelete('cascade')->onUpdate('cascade');
            $table->date('fecha');
            $table->string('tipoEntrada');
            $table->integer('h1');
            $table->integer('h2');
            $table->integer('h3');
            $table->integer('h4');
            $table->integer('h5');
            $table->integer('h6');
            $table->integer('h7');
            $table->integer('h8');
            $table->integer('h9');
            $table->integer('h10');
            $table->integer('h11');
            $table->integer('h12');
            $table->integer('h13');
            $table->integer('h14');
            $table->integer('h15');
            $table->integer('h16');
            $table->integer('h17');
            $table->integer('h18');
            $table->integer('h19');
            $table->integer('h20');
            $table->integer('h21');
            $table->integer('h22');
            $table->integer('h23');
            $table->integer('h24');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TIE_contadorPersonas');
        Schema::table('TIE_contadorPersonas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
