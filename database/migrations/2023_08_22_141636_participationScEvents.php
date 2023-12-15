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
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->string('researcherInvolved')->nullable();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('presentationTitle')->nullable();
            $table->string('typeOfParticipation')->nullable();
            $table->boolean('otherParticipation')->nullable();
            $table->string('typeEvent')->nullable();
            $table->boolean('other')->nullable();
            $table->string('eventName')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->string('nameOfParticipants')->nullable();
            $table->string('progressReport')->nullable();
            $table->string('file')->nullable();
            $table->string('comments')->nullable();
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
