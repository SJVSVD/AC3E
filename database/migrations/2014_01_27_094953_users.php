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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idRole')->unsigned();
            $table->foreign("idRole")->references("id")->on("rolesUsers")->onDelete("cascade")->onUpdate("cascade");
            $table->bigInteger('idResearchLine')->unsigned();
            $table->foreign("idResearchLine")->references("id")->on("researchLines")->onDelete("cascade")->onUpdate("cascade");
            $table->string('profilePicture')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('estado')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
