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
            $table->string('nameOfPostdoc')->nullable();
            $table->string('identification')->nullable();
            $table->string('runOrPassport')->nullable();
            $table->string('gender')->nullable();
            $table->string('researchTopic')->nullable();
            $table->string('supervisorName')->nullable();
            $table->string('resourcesProvided')->nullable();
            $table->string('fundingSource')->nullable();
            $table->year('startYear')->nullable();
            $table->year('endingYear')->nullable();
            $table->boolean('privateSector')->nullable();
            $table->boolean('academy1')->nullable();
            $table->boolean('business')->nullable();
            $table->boolean('ownEntrepreneurship')->nullable();
            $table->boolean('publicSector')->nullable();
            $table->boolean('government')->nullable();
            $table->boolean('academy2')->nullable();
            $table->boolean('socialOng')->nullable();
            $table->boolean('inTheCenter')->nullable();
            $table->boolean('noneOfTheAbove')->nullable();
            $table->string('institutionName')->nullable();
            $table->string('nameOfResearch')->nullable();
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
        Schema::dropIfExists('technologyKnowledge');
        Schema::table('technologyKnowledge', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
