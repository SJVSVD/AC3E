<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleColumnToProgressReportGoals extends Migration {
    public function up() {
        Schema::table('progress_report_goals', function (Blueprint $table) {
            // Agregar el campo 'module'
            $table->string('module')->nullable()->after('researcher_type_id');
        });
    }

    public function down() {
        Schema::table('progress_report_goals', function (Blueprint $table) {
            // Eliminar el campo 'module'
            $table->dropColumn('module');
        });
    }
}
