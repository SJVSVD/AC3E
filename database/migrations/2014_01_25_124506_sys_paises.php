<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('SYS_paises', function (Blueprint $table) {
            $table->id();
            $table->string('pais')->unique();
            $table->string('capital');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('SYS_paises');
        Schema::table('SYS_paises', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
