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
        Schema::create('SYS_empresa_marca', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('idEmpresa');
            $table->foreign("idEmpresa")->references("id")->on("SYS_empresas")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idMarca');
            $table->foreign("idMarca")->references("id")->on("SYS_marcas")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('SYS_empresa_marca');
        Schema::table('SYS_empresa_marca', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
