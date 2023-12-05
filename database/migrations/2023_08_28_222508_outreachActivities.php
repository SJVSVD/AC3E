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
        Schema::create('outreachActivities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('researcherInvolved')->nullable();
            $table->string('responsability')->nullable();
            $table->string('activityType')->nullable();
            $table->boolean('otherType')->nullable();
            $table->string('activityName')->nullable();
            $table->string('activityDescription')->nullable();
            $table->date('date')->nullable();
            $table->integer('attendantsAmount')->nullable();
            $table->integer('duration')->nullable();
            $table->string('country')->nullable();
            $table->string('placeRegion')->nullable();
            $table->string('city')->nullable();
            $table->boolean('undergraduateStudents')->nullable();
            $table->boolean('primaryEducationStudents')->nullable();
            $table->boolean('secondaryEducationStudents')->nullable();
            $table->boolean('generalCommunity')->nullable();
            $table->boolean('companiesIndustriesServices')->nullable();
            $table->boolean('schoolTeachers')->nullable();
            $table->boolean('governmentOfficial')->nullable();
            $table->boolean('other')->nullable();
            $table->string('nameOfTheMainResponsible')->nullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('outreachActivities');
        Schema::table('outreachActivities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
