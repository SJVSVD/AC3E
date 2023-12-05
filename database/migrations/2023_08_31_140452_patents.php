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
        Schema::create('patents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idUsuario')->unsigned();
            $table->foreign("idUsuario")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('status');
            $table->string('nameOfPatent')->nullable();
            $table->string('ipType')->nullable();
            $table->string('authors')->nullable();
            $table->string('institutionOwner')->nullable();
            $table->string('countryOfRegistration')->nullable();
            $table->date('applicationDate')->nullable();
            $table->date('grantDate')->nullable();
            $table->string('applicationStatus')->nullable();
            $table->string('registrationNumber')->nullable();
            $table->string('applicationGranted')->nullable();
            $table->string('researcherInvolved')->nullable();
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
        Schema::dropIfExists('patents');
        Schema::table('patents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
