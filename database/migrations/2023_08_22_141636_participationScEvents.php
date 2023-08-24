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
        Schema::create('participationScEvents', function (Blueprint $table) {
            $table->id();
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('typeEvent')->nullable();
            $table->boolean('other')->nullable();
            $table->string('eventName')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->string('nameOfResearch')->nullable();
            $table->string('nameOfParticipants')->nullable();
            $table->integer('progressReport')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('participationScEvents');
        Schema::table('participationScEvents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};