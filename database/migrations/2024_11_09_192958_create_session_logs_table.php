<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionLogsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('session_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con la tabla de usuarios
            $table->string('event_type'); // Tipo de evento (e.g., login, logout, password_reset, etc.)
            $table->text('description')->nullable(); // Detalles adicionales sobre el evento
            $table->timestamp('timestamp')->useCurrent(); // Marca de tiempo del evento
            $table->string('ip_address', 45)->nullable(); // Dirección IP del usuario
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('session_logs');
    }
}
