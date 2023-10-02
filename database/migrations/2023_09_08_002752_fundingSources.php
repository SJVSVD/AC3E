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
        Schema::create('fundingSources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('nameOfResearch')->nullable();
            $table->string('typeSources')->nullable();
            $table->string('nameOfInstitution')->nullable();
            $table->string('programContest')->nullable();
            $table->string('projectTitle')->nullable();
            $table->string('principalResearcher')->nullable();
            $table->date('startDate')->nullable();
            $table->date('finishDate')->nullable();
            $table->string('comments')->nullable();
            $table->integer('inCash')->nullable();
            $table->string('typeOfCollaboration')->nullable();
            $table->string('state')->nullable();
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
        Schema::dropIfExists('fundingSources');
        Schema::table('fundingSources', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
