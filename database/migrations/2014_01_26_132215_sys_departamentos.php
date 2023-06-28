<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('departamento')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_departamentos');
        Schema::table('SYS_departamentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
