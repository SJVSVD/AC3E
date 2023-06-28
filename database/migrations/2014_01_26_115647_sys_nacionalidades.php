<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_nacionalidades', function (Blueprint $table) {
            $table->id();
            $table->string('nacionalidad')->unique();
            $table->integer('idPais');
            $table->foreign("idPais")->references("id")->on("SYS_paises")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_nacionalidades');
        Schema::table('SYS_nacionalidades', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
