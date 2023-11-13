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
            $table->string('agentType')->nullable();
            $table->string('typeOfConnection')->nullable();
            $table->string('placeWhereWasExecuted')->nullable();
            $table->string('internationalNational')->nullable();
            $table->boolean('participationPublicPolicies')->nullable();
            $table->string('researcherInvolved')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->longText('resultsGoals')->nullable();
            $table->string('nameOfOrganization')->nullable();
            $table->string('countryOrigin')->nullable();
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
