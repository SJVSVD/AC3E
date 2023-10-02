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
        Schema::create('publicPrivate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('nameOfActivity')->nullable();
            $table->boolean('developmentalStudies')->nullable();
            $table->boolean('projectImplementation')->nullable();
            $table->boolean('training')->nullable();
            $table->boolean('prospectiveActivity')->nullable();
            $table->boolean('installationOfScientist')->nullable();
            $table->boolean('temporaryInstallation')->nullable();
            $table->boolean('internationalAgreements')->nullable();
            $table->boolean('participationsInInstances')->nullable();
            $table->boolean('internationalExchange')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->longText('resultsGoals')->nullable();
            $table->string('nameOfOrganization')->nullable();
            $table->string('countryOrigin')->nullable();
            $table->boolean('privateSector')->nullable();
            $table->boolean('academy1')->nullable();
            $table->boolean('business')->nullable();
            $table->boolean('ownEntrepreneurship')->nullable();
            $table->boolean('publicSector')->nullable();
            $table->boolean('government')->nullable();
            $table->boolean('academy2')->nullable();
            $table->boolean('socialOng')->nullable();
            $table->boolean('education')->nullable();
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
        Schema::dropIfExists('publicPrivate');
        Schema::table('publicPrivate', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
