<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_userRelatedTimers', function (Blueprint $table) {
            $table->id();
            $table->string('sistema');
            $table->string('identificadorEspecifico')->nullable();
            $table->integer('idUsuario');
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('timerName');
            $table->integer('timeToWait');
            $table->string('browser');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_userRelatedTimers');
        Schema::table('SYS_userRelatedTimers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
