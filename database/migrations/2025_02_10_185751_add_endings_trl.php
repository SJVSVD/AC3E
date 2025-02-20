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
        Schema::table('technologyKnowledge', function (Blueprint $table) {
            $table->year('yearEnding')->nullable()->after('year');
            $table->string('monthEnding')->nullable()->after('month');
            $table->integer('actualTrl')->nullable()->after('nameOfBeneficiary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technologyKnowledge', function (Blueprint $table) {
            $table->dropColumn(['yearEnding', 'monthEnding','actualTrl']);
        });
    }
};
