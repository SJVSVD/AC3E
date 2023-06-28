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
        Schema::create('TIE_detallesSoliPrecios', function (Blueprint $table) {
            $table->id();
            $table->integer('idSoliPrecio');
            $table->foreign("idSoliPrecio")->references("id")->on("TIE_soliPrecios")->onDelete("cascade")->onUpdate("cascade");
            $table->string('tipoEtiqueta');
            $table->integer('precio');
            $table->integer('cantidad');
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
        Schema::dropIfExists('TIE_detallesSoliPrecios');
        Schema::table('TIE_detallesSoliPrecios', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
