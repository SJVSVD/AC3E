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
        Schema::create('scCollaborations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->integer('moduleType');
            $table->string('status');
            $table->string('institutionCollaborates')->nullable();
            $table->string('peopleInvolved')->nullable();
            $table->string('activityType')->nullable();
            $table->string('collaborationType')->nullable();
            $table->boolean('otherCollaboration')->nullable();
            $table->string('activityName')->nullable();
            $table->string('countryOrigin')->nullable();
            $table->string('cityOrigin')->nullable();
            $table->string('countryDestination')->nullable();
            $table->string('cityDestination')->nullable();
            $table->date('beginningDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->string('nameOfResearch')->nullable();
            $table->integer('progressReport')->nullable();
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
        Schema::dropIfExists('scCollaborations');
        Schema::table('scCollaborations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
