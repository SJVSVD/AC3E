<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('idDepartamento');
            $table->foreign("idDepartamento")->references("id")->on("SYS_departamentos")->onDelete("cascade")->onUpdate("cascade");
            $table->string('area');
            $table->string('idDepArea')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_areas');
        Schema::table('SYS_areas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
