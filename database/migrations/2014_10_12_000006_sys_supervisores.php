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
        Schema::create('SYS_supervisores', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado');
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idSucursal');
            $table->foreign("idSucursal")->references("id")->on("SYS_sucursales")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('SYS_supervisores');
        Schema::table('SYS_supervisores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
