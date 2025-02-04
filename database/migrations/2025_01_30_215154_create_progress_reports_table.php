<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('progress_report_goals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('research_line_id')->nullable()->constrained('researchLines')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Se almacena un JSON con objetivos por año
            $table->json('goals')->nullable();

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('progress_reports');
    }
};
