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
        Schema::create('thesisStudents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('identification');
            $table->string('studentName');
            $table->string('runOrPassport');
            $table->char('gender');
            $table->string('studentMail');
            $table->string('thesisStatus');
            $table->string('thesisTitle');
            $table->string('academicDegree');
            $table->string('degreeDenomination');
            $table->string('tutorName')->nullable();
            $table->string('tutorInstitution')->nullable();
            $table->string('cotutorName')->nullable();
            $table->string('cotutorInstitution')->nullable();
            $table->string('otherName')->nullable();
            $table->string('otherInstitution')->nullable();
            $table->string('university');
            $table->year('yearStart');
            $table->year('yearThesisEnd');
            $table->string('resourcesCenter');
            $table->string('posteriorArea');
            $table->string('institutionPosteriorArea');
            $table->string('comments');
            $table->string('thesisExtract')->nullable();
            $table->integer('progressReport')->nullable();
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
        Schema::dropIfExists('thesisStudents');
        Schema::table('thesisStudents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
