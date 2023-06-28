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
        Schema::create('SYS_proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('rut')->unique();
            $table->string('razonSocial');
            $table->string('codSocioNegocio')->unique()->nullable();
            $table->integer('idAuthAso1')->nullable();
            $table->foreign("idAuthAso1")->references("id")->on("SYS_autorizadores")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('idAuthAso2')->nullable();
            $table->foreign("idAuthAso2")->references("id")->on("SYS_autorizadores")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('SYS_proveedores');
        Schema::table('SYS_proveedores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
