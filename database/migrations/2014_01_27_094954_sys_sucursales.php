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
        Schema::create('SYS_sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->string('codigo')->unique();
            $table->string('centroCosto')->nullable();
            $table->string('nombre');
            $table->integer('idEmpresaMarca');
            $table->foreign("idEmpresaMarca")->references("id")->on("SYS_empresa_marca")->onDelete("cascade")->onUpdate("cascade");
            $table->string('direccion');
            $table->integer('idZona');
            $table->foreign("idZona")->references("id")->on("SYS_zonas")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idPais');
            $table->foreign("idPais")->references("id")->on("SYS_paises")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idRegion');
            $table->foreign("idRegion")->references("id")->on("SYS_regiones")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idComuna');
            $table->foreign("idComuna")->references("id")->on("SYS_comunas")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idTipoSucursal');
            $table->foreign("idTipoSucursal")->references("id")->on("SYS_tipoSucursal")->onDelete("cascade")->onUpdate("cascade");
            $table->string('telefono')->nullable();
            $table->string('bodega')->nullable();
            $table->string('email')->nullable();
            $table->string('tipoCuentaPersonas')->nullable();
            $table->integer('idUsuario')->nullable();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('SYS_sucursales');
        Schema::table('SYS_sucursales', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
