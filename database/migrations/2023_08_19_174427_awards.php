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
            $table->bigIncrements('id');
            $table->string('status')->nullable();
            $table->string('researcherInvolved')->nullable();
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('awardeeName')->nullable();
            $table->string('awardName')->nullable();
            $table->year('year')->nullable();
            $table->string('month')->nullable();
            $table->string('contributionAwardee', 500)->nullable();
            $table->string('institution')->nullable();
            $table->string('country')->nullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('awards');
        Schema::table('awards', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
