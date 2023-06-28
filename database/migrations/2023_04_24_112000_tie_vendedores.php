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
        Schema::create('TIE_vendedores', function (Blueprint $table) {
            $table->id();
            $table->string('codigoVendedor')->unique();
            $table->string('rut')->unique();
            $table->string('nombre1');
            $table->string('nombre2')->nullable();
            $table->string('apellido1');
            $table->string('apellido2');
            $table->boolean('enviarTPV')->nullable();
            $table->integer('idEmpresa');
            $table->foreign("idEmpresa")->references("id")->on("SYS_empresas")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('TIE_vendedores');
        Schema::table('TIE_vendedores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
