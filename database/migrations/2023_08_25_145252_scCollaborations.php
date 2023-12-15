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
            $table->string('researcherInvolved')->nullable();
            $table->string('studentOrResearcher')->nullable();
            $table->string('activityType')->nullable();
            $table->string('activityName')->nullable();
            $table->boolean('otherActivity')->nullable();
            $table->string('countryOrigin')->nullable();
            $table->string('cityOrigin')->nullable();
            $table->string('countryDestination')->nullable();
            $table->string('cityDestination')->nullable();
            $table->date('beginningDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->string('nameOfAC3EMember')->nullable();
            $table->string('nameOfExternalResearcher')->nullable();
            $table->string('collaborationStay')->nullable();
            $table->boolean('otherStay')->nullable();
            $table->string('progressReport')->nullable();
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
        Schema::dropIfExists('scCollaborations');
        Schema::table('scCollaborations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
