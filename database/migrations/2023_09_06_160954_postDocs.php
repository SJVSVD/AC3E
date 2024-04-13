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
        Schema::create('postDocs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('researcherInvolved')->nullable();
            $table->string('nameOfPostdoc')->nullable();
            $table->string('identification')->nullable();
            $table->string('runOrPassport')->nullable();
            $table->string('gender')->nullable();
            $table->string('personalEmail')->nullable();
            $table->string('researchTopic')->nullable();
            $table->string('supervisorName')->nullable();
            $table->string('resourcesProvided')->nullable();
            $table->string('fundingSource')->nullable();
            $table->year('startYear')->nullable();
            $table->year('endingYear')->nullable();
            $table->string('startMonth')->nullable();
            $table->string('endMonth')->nullable();
            $table->string('institutionName')->nullable();
            $table->string('comments')->nullable();
            $table->string('progressReport')->nullable();
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
        Schema::dropIfExists('technologyKnowledge');
        Schema::table('technologyKnowledge', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
