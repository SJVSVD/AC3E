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
        Schema::table('publicPrivate', function (Blueprint $table) {
            // Añadir el campo 'file' como string
            $table->string('file')->nullable()->after('id');

            // Añadir el campo 'is_link' como booleano con valor por defecto false
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
        Schema::table('publicPrivate', function (Blueprint $table) {
            // Eliminar los campos agregados en caso de rollback
            $table->dropColumn(['file', 'is_link']);
        });
    }
};
