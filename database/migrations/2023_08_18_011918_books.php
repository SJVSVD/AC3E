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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->string('workType')->nullable();
            $table->integer('centerResearcher');
            $table->foreign("centerResearcher")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('bookAuthors')->nullable();
            $table->string('chapterAuthors')->nullable();
            $table->string('bookTitle')->nullable();
            $table->string('chapterTitle')->nullable();
            $table->string('firstPage')->nullable();
            $table->string('lastPage')->nullable();
            $table->string('editorialCityCountry')->nullable();
            $table->year('year')->nullable();
            $table->string('nameOfResearchLine')->nullable();
            $table->integer('progressReport')->nullable();
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
        Schema::dropIfExists('books');
        Schema::table('books', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};