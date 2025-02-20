<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear la tabla para el modo Performance Data Collection
        Schema::create('performance_data_collection_mode', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->dateTime('deadline');
            $table->timestamps();
        });

        // Modificar la tabla de usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable()->after('updated_at');
            $table->boolean('notified_complete')->default(false)->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar la tabla del modo Performance Data Collection
        Schema::dropIfExists('performance_data_collection_mode');

        // Revertir cambios en la tabla de usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['deadline', 'notified_complete']);
        });
    }
};
