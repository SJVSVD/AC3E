<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_tipoDocFactura', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_tipoDocFactura');
        Schema::table('CTB_tipoDocFactura', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};