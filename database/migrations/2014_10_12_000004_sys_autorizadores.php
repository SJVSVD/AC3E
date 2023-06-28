<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_autorizadores', function (Blueprint $table) {
            $table->id();
            $table->string('identificador');
            $table->integer('idUsuario')->unsigned();
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('levelName');
            $table->boolean('level');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_autorizadores');
        Schema::table('SYS_autorizadores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
