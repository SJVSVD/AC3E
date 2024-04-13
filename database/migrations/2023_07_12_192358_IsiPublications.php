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
        Schema::create('isiPublications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('researcherInvolved')->nullable();
            $table->string('authors', 3500)->nullable();
            $table->string('coauthor')->nullable();
            $table->string('articleTitle',400)->nullable();
            $table->string('journalName')->nullable();
            $table->string('doi')->nullable();
            $table->string('volume')->nullable();
            $table->string('firstPage')->nullable();
            $table->string('lastPage')->nullable();
            $table->string('keywords')->nullable();
            $table->year('yearPublished')->nullable();
            $table->string('month')->nullable();
            $table->string('funding')->nullable();
            $table->boolean('mainResearchers')->nullable();
            $table->boolean('associativeResearchers')->nullable();
            $table->boolean('postDoc')->nullable();
            $table->boolean('thesisStudents')->nullable();
            $table->boolean('nationalExternalResearchers')->nullable();
            $table->boolean('internationalExternalResearchers')->nullable();
            $table->string('comments',1500)->nullable();
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
        Schema::dropIfExists('isiPublications');
        Schema::table('isiPublications', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
