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
        Schema::table('organizationsScEvents', function (Blueprint $table) {
            // Añadir un campo booleano que indica si 'file' es un link o no
            $table->boolean('is_link')->default(false)->after('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizationsScEvents', function (Blueprint $table) {
            // Eliminar el campo 'is_link' en caso de revertir la migración
            $table->dropColumn('is_link');
        });
    }
};
