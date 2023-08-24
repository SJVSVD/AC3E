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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->integer('awardeeName')->nullable();
            $table->foreign("awardeeName")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('awardName')->nullable();
            $table->year('year')->nullable();
            $table->string('contributionAwardee')->nullable();
            $table->string('institution')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('awards');
        Schema::table('awards', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};