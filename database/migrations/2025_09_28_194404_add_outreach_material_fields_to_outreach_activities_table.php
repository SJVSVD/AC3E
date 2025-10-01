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
        Schema::table('outreachActivities', function (Blueprint $table) {
            $table->string('materialType')->nullable();
            $table->string('materialName')->nullable();
            $table->date('publicationDate')->nullable();
            $table->string('publicationMean')->nullable();
            $table->string('publicationOther')->nullable();
            $table->integer('downloads')->nullable();
            $table->integer('twitterMentions')->nullable();
            $table->integer('facebookMentions')->nullable();
            $table->integer('otherMentions')->nullable();
        });
    }

    public function down()
    {
        Schema::table('outreachActivities', function (Blueprint $table) {
            $table->dropColumn([
                'materialType',
                'materialName',
                'publicationDate',
                'publicationMean',
                'publicationOther',
                'downloads',
                'twitterMentions',
                'facebookMentions',
                'otherMentions',
            ]);
        });
    }

};
