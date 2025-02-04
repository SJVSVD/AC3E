<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResearcherTypeIdToProgressReportGoals extends Migration {
    public function up() {
        Schema::table('progress_report_goals', function (Blueprint $table) {
            $table->unsignedBigInteger('researcher_type_id')->nullable()->after('user_id');
            $table->foreign('researcher_type_id')->references('id')->on('rolesUsers')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('progress_report_goals', function (Blueprint $table) {
            $table->dropForeign(['researcher_type_id']);
            $table->dropColumn('researcher_type_id');
        });
    }
}
