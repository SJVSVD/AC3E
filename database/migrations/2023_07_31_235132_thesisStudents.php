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
            $table->string('researcherInvolved')->nullable();
            $table->string('identification')->nullable();
            $table->string('studentName')->nullable();
            $table->string('runOrPassport')->nullable();
            $table->char('gender')->nullable();
            $table->string('studentMail')->nullable();
            $table->string('thesisStatus')->nullable();
            $table->string('thesisTitle', 500)->nullable();
            $table->string('academicDegree')->nullable();
            $table->string('degreeDenomination')->nullable();
            $table->string('tutorName')->nullable();
            $table->string('tutorInstitution')->nullable();
            $table->string('cotutorName')->nullable();
            $table->string('cotutorInstitution')->nullable();
            $table->string('otherName')->nullable();
            $table->string('otherInstitution')->nullable();
            $table->string('university')->nullable();
            $table->year('yearStart')->nullable();
            $table->year('yearThesisEnd')->nullable();
            $table->string('monthEnd')->nullable();
            $table->string('monthStart')->nullable();
            $table->string('resourcesCenter')->nullable();
            $table->string('posteriorArea')->nullable();
            $table->string('institutionPosteriorArea')->nullable();
            $table->string('comments')->nullable();
            $table->string('file')->nullable();
            $table->string('progressReport')->nullable();
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
