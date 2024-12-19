<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('isiPublications', function (Blueprint $table) {
            $table->string('quartile', 10)->nullable()->after('lastPage');
            $table->decimal('impactFactor', 8, 3)->nullable()->after('quartile');
        });
    }

    public function down()
    {
        Schema::table('isiPublications', function (Blueprint $table) {
            $table->dropColumn(['quartile', 'impactFactor']);
        });
    }
};
