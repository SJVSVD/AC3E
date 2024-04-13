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
        Schema::create('technologyKnowledge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->boolean('technologyTransfer')->nullable();
            $table->boolean('knowledgeTransfer')->nullable();
            $table->string('typeOfTransfer')->nullable();
            $table->string('nameOfBeneficiary')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('placeRegion')->nullable();
            $table->year('year')->nullable();
            $table->string('month')->nullable();
            $table->string('nameOfResearch')->nullable();
            $table->string('comments')->nullable();
            $table->string('description',500)->nullable();
            $table->string('nameOfInstitutionInvolved')->nullable();
            $table->string('researcherInvolved')->nullable();
            $table->boolean('externalResearcher')->nullable();
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
