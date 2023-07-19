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
            $table->id();
            $table->integer('idUsuario');
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('authors');
            $table->string('coauthor')->nullable();
            $table->string('articleTitle');
            $table->string('journalName');
            $table->string('doi');
            $table->string('volume');
            $table->string('firstPage');
            $table->string('lastPage');
            $table->year('yearPublished');
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
