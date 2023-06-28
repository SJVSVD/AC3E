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
        Schema::create('SYS_manuales', function (Blueprint $table) {
            $table->id();
            $table->string('modulo');
            $table->date('fecha');
            $table->string('titulo');
            $table->string('archivo')->nullable();
            $table->string('url', 300)->nullable();
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
        Schema::dropIfExists('SYS_manuales');
        Schema::table('SYS_manuales', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
