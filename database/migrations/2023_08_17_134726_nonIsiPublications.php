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
        Schema::create('nonIsiPublications', function (Blueprint $table) {
            $table->id();
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('authors')->nullable();
            $table->string('coauthor')->nullable();
            $table->string('articleTitle')->nullable();
            $table->string('journalName')->nullable();
            $table->string('volume')->nullable();
            $table->string('firstPage')->nullable();
            $table->string('lastPage')->nullable();
            $table->year('yearPublished')->nullable();
            $table->string('funding')->nullable();
            $table->boolean('mainResearchers')->nullable();
            $table->boolean('associativeResearchers')->nullable();
            $table->boolean('postDoc')->nullable();
            $table->boolean('thesisStudents')->nullable();
            $table->boolean('nationalExternalResearchers')->nullable();
            $table->boolean('internationalExternalResearchers')->nullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('nonIsiPublications');
        Schema::table('nonIsiPublications', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
