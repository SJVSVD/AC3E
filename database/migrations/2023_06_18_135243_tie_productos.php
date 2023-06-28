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
        Schema::create('TIE_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('numNCV');
            $table->foreign("numNCV")->references("notaCredito")->on("TIE_notasCreditos")->onDelete("cascade")->onUpdate("cascade");
            $table->string('codigo');
            $table->string('descripcion');
            $table->integer('cantidad');
            $table->integer('precio');
            $table->string('nroLinea');
            $table->string('motivo')->nullable();
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
        Schema::dropIfExists('TIE_productos');
        Schema::table('TIE_productos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
