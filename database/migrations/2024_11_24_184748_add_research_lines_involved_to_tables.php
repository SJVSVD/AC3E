<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResearchLinesInvolvedToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Lista de tablas que necesitan el campo 'researchLinesInvolved'
        $tablas = [
            'isiPublications',
            'nonIsiPublications',
            'books',
            'awards',
            'organizationsScEvents',
            'participationScEvents',
            'scCollaborations',
            'thesisStudents',
            'postDocs',
            'outreachActivities',
            'patents',
            'publicPrivate',
            'technologyKnowledge',
            'fundingSources',
        ];

        foreach ($tablas as $tabla) {
            // Agregar el campo 'researchLinesInvolved' a cada tabla si no existe
            Schema::table($tabla, function (Blueprint $table) use ($tabla) {
                if (!Schema::hasColumn($tabla, 'researchLinesInvolved')) {
                    $table->text('researchLinesInvolved')->nullable()->after('researcherInvolved');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Lista de tablas que tienen el campo 'researchLinesInvolved'
        $tablas = [
            'isiPublications',
            'nonIsiPublications',
            'books',
            'awards',
            'organizationsScEvents',
            'participationScEvents',
            'scCollaborations',
            'thesisStudents',
            'postDocs',
            'outreachActivities',
            'patents',
            'publicPrivate',
            'technologyKnowledge',
            'fundingSources',
        ];

        foreach ($tablas as $tabla) {
            // Eliminar el campo 'researchLinesInvolved' de cada tabla si existe
            Schema::table($tabla, function (Blueprint $table) use ($tabla) {
                if (Schema::hasColumn($tabla, 'researchLinesInvolved')) {
                    $table->dropColumn('researchLinesInvolved');
                }
            });
        }
    }
}
