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
            $table->id();
            $table->integer('idColaborador')->nullable();
            $table->foreign("idColaborador")->references("id")->on("SYS_colaboradores")->onDelete("cascade")->onUpdate("cascade");
            $table->string('rut')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('fofiAsignado')->default(0);
            $table->integer('idTipoUsuario')->nullable();
            $table->foreign("idTipoUsuario")->references("id")->on("SYS_tipoUsuarios")->onDelete("cascade")->onUpdate("cascade");
            $table->longText('profilePicture')->nullable();
            $table->boolean('estado')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
