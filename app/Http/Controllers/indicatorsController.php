<?php

namespace App\Http\Controllers;

use App\Models\awards;
use App\Models\books;
use App\Models\extraTables;
use App\Models\fundingSources;
use App\Models\isiPublication;
use App\Models\nonIsiPublication;
use App\Models\organizationsScEvents;
use App\Models\outreachActivities;
use App\Models\participationScEvents;
use App\Models\patents;
use App\Models\postDoc;
use App\Models\publicPrivate;
use App\Models\scCollaborations;
use App\Models\technologyKnowledge;
use App\Models\thesisStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class indicatorsController extends Controller
{

    public function getFilteredRecordsByModule(Request $request)
    {
        try {
            $moduleName = $request->input('module');
            $lineName = $request->input('line');
            $personId = $request->input('person');
    
            // Validar el módulo
            $moduleClass = $this->getModuleClass($moduleName);
    
            if (!$moduleClass) {
                return response()->json(['error' => 'Invalid module name'], 400);
            }
    
            $recordsByYear = [];
    
            $model = new $moduleClass;
    
            // Construir consulta base
            $query = $model->select('progressReport', DB::raw('COUNT(*) as total'));
    
            // Filtrar por persona si está seleccionada
            if ($personId) {
                $user = User::find($personId);
                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }
    
                $normalizedUserName = strtolower($user->name);
                $query->where(function ($query) use ($personId, $normalizedUserName) {
                    $query->where('idUsuario', $personId)
                          ->orWhereRaw('LOWER(researcherInvolved) LIKE ?', ["%{$normalizedUserName}%"]);
                });
            }
    
            // Filtrar por línea de investigación si está seleccionada
            if ($lineName) {
                $normalizedLineName = strtolower($lineName);
                $query->whereRaw('LOWER(researchLinesInvolved) LIKE ?', ["%{$normalizedLineName}%"]);
            }
    
            // Agrupar registros por progressReport y contar
            $groupedRecords = $query->groupBy('progressReport')
                ->orderBy('progressReport', 'asc')
                ->get();
    
            foreach ($groupedRecords as $record) {
                $progressReportYear = $record->progressReport;
    
                if (!isset($recordsByYear[$progressReportYear])) {
                    $recordsByYear[$progressReportYear] = 0;
                }
    
                $recordsByYear[$progressReportYear] += $record->total;
            }
    
            // Ordenar por año de progressReport
            ksort($recordsByYear);
    
            // Obtener el progressReport actual desde extraTables
            $currentProgressReport = extraTables::where('name', 'progressReport')->value('value');
    
            return response()->json([
                'currentProgressReport' => $currentProgressReport,
                'recordsByYear' => $recordsByYear,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching filtered records by module: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    

    public function getPublicationsByResearchLine($lineName)
    {
        try {
            // Normalizar el nombre de la línea de investigación
            $normalizedLineName = strtolower($lineName);
    
            // Obtener el progressReport actual desde extraTables
            $currentProgressReport = extraTables::where('name', 'progressReport')->value('value');
    
            // Módulos que se deben buscar
            $modules = [
                isiPublication::class,
                nonIsiPublication::class,
                books::class,
                awards::class,
                organizationsScEvents::class,
                participationScEvents::class,
                scCollaborations::class,
                thesisStudent::class,
                postDoc::class,
                outreachActivities::class,
                patents::class,
                publicPrivate::class,
                technologyKnowledge::class,
                fundingSources::class
            ];
    
            $recordsByYear = [];
    
            foreach ($modules as $module) {
                $model = new $module;
    
                // Agrupar registros por progressReport y filtrar por línea de investigación
                $groupedRecords = $model
                    ->select('progressReport', DB::raw('COUNT(*) as total'))
                    ->whereRaw('LOWER(researchLinesInvolved) LIKE ?', ["%{$normalizedLineName}%"])
                    ->groupBy('progressReport')
                    ->get();
    
                foreach ($groupedRecords as $record) {
                    $progressReportYear = $record->progressReport;
    
                    if (!isset($recordsByYear[$progressReportYear])) {
                        $recordsByYear[$progressReportYear] = 0;
                    }
    
                    $recordsByYear[$progressReportYear] += $record->total;
                }
            }
    
            // Ordenar por año de progressReport
            ksort($recordsByYear);
    
            return response()->json([
                'currentProgressReport' => $currentProgressReport,
                'recordsByYear' => $recordsByYear
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching records by research line: ' . $e->getMessage()], 500);
        }
    }
    

    public function getPersonRecordsByProgressReport($userId)
    {
        try {
            // Obtener el nombre del usuario y normalizarlo
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
    
            $normalizedUserName = $this->normalizeString($user->name);
    
            // Obtener el progressReport actual desde extraTables
            $currentProgressReport = extraTables::where('name', 'progressReport')->value('value');
    
            // Módulos que se deben buscar
            $modules = [
                'isiPublications' => isiPublication::class,
                'nonIsiPublications' => nonIsiPublication::class,
                'books' => books::class,
                'awards' => awards::class,
                'organizationsScEvents' => organizationsScEvents::class,
                'participationScEvents' => participationScEvents::class,
                'scCollaborations' => scCollaborations::class,
                'thesisStudents' => thesisStudent::class,
                'postDocs' => postDoc::class,
                'outreachActivities' => outreachActivities::class,
                'patents' => patents::class,
                'publicPrivate' => publicPrivate::class,
                'technologyKnowledge' => technologyKnowledge::class,
                'fundingSources' => fundingSources::class
            ];
    
            $recordsByYear = [];
    
            foreach ($modules as $moduleName => $module) {
                $model = new $module;
    
                // Manejar el caso especial para `books`
                $query = $model->select('progressReport', DB::raw('COUNT(*) as total'));
    
                if ($moduleName === 'books') {
                    $query->where('centerResearcher', $userId);
                } else {
                    $query->where(function ($query) use ($userId, $normalizedUserName) {
                        $query->where('idUsuario', $userId)
                              ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(researcherInvolved, '\r', ''), '\n', ''), '\t', '')) LIKE ?", ["%$normalizedUserName%"]);
                    });
                }
    
                // Agrupar registros por progressReport y contar
                $groupedRecords = $query->groupBy('progressReport')->get();
    
                foreach ($groupedRecords as $record) {
                    $progressReportYear = $record->progressReport;
    
                    if (!isset($recordsByYear[$progressReportYear])) {
                        $recordsByYear[$progressReportYear] = 0;
                    }
    
                    $recordsByYear[$progressReportYear] += $record->total;
                }
            }
    
            // Ordenar por año de progressReport
            ksort($recordsByYear);
    
            return response()->json([
                'userName' => $user->name,
                'currentProgressReport' => $currentProgressReport,
                'recordsByYear' => $recordsByYear
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching person records: ' . $e->getMessage()], 500);
        }
    }
    

    public function getGeneralRecordsByProgressReport()
        {
            try {
                // Obtener el progressReport actual desde extraTables
                $currentProgressReport = extraTables::where('name', 'progressReport')->value('value');

                // Módulos que se deben buscar
                $modules = [
                    isiPublication::class,
                    nonIsiPublication::class,
                    books::class,
                    awards::class,
                    organizationsScEvents::class,
                    participationScEvents::class,
                    scCollaborations::class,
                    thesisStudent::class,
                    postDoc::class,
                    outreachActivities::class,
                    patents::class,
                    publicPrivate::class,
                    technologyKnowledge::class,
                    fundingSources::class
                ];

                $recordsByYear = [];

                foreach ($modules as $module) {
                    $model = new $module;

                    // Agrupar registros por progressReport y contar
                    $groupedRecords = $model
                        ->select('progressReport', DB::raw('COUNT(*) as total'))
                        ->groupBy('progressReport')
                        ->get();

                    foreach ($groupedRecords as $record) {
                        $progressReportYear = $record->progressReport;

                        if (!isset($recordsByYear[$progressReportYear])) {
                            $recordsByYear[$progressReportYear] = 0;
                        }

                        $recordsByYear[$progressReportYear] += $record->total;
                    }
                }

                // Ordenar por año de progressReport
                ksort($recordsByYear);

                return response()->json([
                    'currentProgressReport' => $currentProgressReport,
                    'recordsByYear' => $recordsByYear
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error fetching general records: ' . $e->getMessage()], 500);
            }
        }

    
    
    // Función para normalizar cadenas
    private function normalizeString($string)
    {
        $string = strtolower($string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);
        return trim($string);
    }

    public function getPublicationsByProgressReport()
    {
        // Agrupar las publicaciones por 'progressReport' y contar cuántas publicaciones tiene cada grupo
        $groupedPublications = isiPublication::select('progressReport', DB::raw('count(*) as total'))
            ->groupBy('progressReport')
            ->orderBy('progressReport', 'asc')
            ->get();
    
        return response()->json($groupedPublications);  // Devuelve el resultado como JSON
    }

    //Funcion para conseguir los indicadores de la pagina segun ciertos requisitos
    public function getIndicators()
    {
        $progressReport = extraTables::where('name', 'progressReport')->pluck('value');

        $data = [
            ['name' => 'Number of WoS Publications', 'value' => isiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Non-WoS Publications', 'value' => nonIsiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Patent Applications', 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'In Progress')->count()],
            ['name' => "Number of Patent Application's granted", 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'Approved')->count()],
            ['name' => 'Number of Licenses and/or Technology Transfer Agreements applied and/or granted', 'value' => technologyKnowledge::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of PhDs and Post-Doctorates inserted into the industry', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')
            ->whereNotNull('thesisStatus')
            ->where('academicDegree', 'PhD degree')
            ->where('thesisStatus', 1)
            ->whereIn('posteriorArea', ['Business', 'Own entrepreneurship', 'Social-ONG', 'Government'])
            ->count()],
            ['name' => 'Number of Activities of Participation in the definition of public policies', 'value' => publicPrivate::where('progressReport', $progressReport)->whereNotNull('participationPublicPolicies')->where('participationPublicPolicies', 1)->count()],
            ['name' => 'Number of Completed master theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'PhD degree')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed Ph.D. theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Master o equivalent')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed undergraduate theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Undergraduate degree or profesional title')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Postdoc working the center', 'value' => postDoc::where('progressReport', $progressReport)->whereNotNull('endingYear')->where('endingYear', '>=', now()->year)->count()],
            ['name' => 'Number of Visiting researchers', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('collaborationStay')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where('collaborationStay', 'Long Visit (more than two weeks)')
                ->where('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%')
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers from other centers or projects', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers to other institutions', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'NOT LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of WoS Publications coauthored with international researchers', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('internationalExternalResearchers')->where('internationalExternalResearchers', 1)->count()],
            ['name' => 'Number of WoS Publications written in co-authorship with national researchers from other institutions', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('nationalExternalResearchers')->where('nationalExternalResearchers', 1)->count()],
            ['name' => 'Number of Participations in projects led by other research teams', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')->where('moduleType', 0)->where('activityName', 'LIKE', '%Participation in R&D Projects directed by other Researcher (external)%')->count()],
            ['name' => 'Number of outreach and/or extension activities', 'value' => outreachActivities::where('progressReport', $progressReport)->count()],
            ['name' => 'Total number of people attending at dissemination and/or outreach activities(workshops,talks,seminars)', 'value' => outreachActivities::where('progressReport', $progressReport)->sum('attendantsAmount')]
        ];
    
        return response()->json($data);
    }
    //Funcion para conseguir los indicadores de la pagina de un año en especifico
    public function getIndicatorsByProgressYear($id)
    {
        $progressReport = $id;

        $data = [
            ['name' => 'Number of WoS Publications', 'value' => isiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Non-WoS Publications', 'value' => nonIsiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Patent Applications', 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'In Progress')->count()],
            ['name' => "Number of Patent Application's granted", 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'Approved')->count()],
            ['name' => 'Number of Licenses and/or Technology Transfer Agreements applied and/or granted', 'value' => technologyKnowledge::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of PhDs and Post-Doctorates inserted into the industry', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')
            ->whereNotNull('thesisStatus')
            ->where('academicDegree', 'PhD degree')
            ->where('thesisStatus', 1)
            ->whereIn('posteriorArea', ['Business', 'Own entrepreneurship', 'Social-ONG', 'Government'])
            ->count()],
            ['name' => 'Number of Activities of Participation in the definition of public policies', 'value' => publicPrivate::where('progressReport', $progressReport)->whereNotNull('participationPublicPolicies')->where('participationPublicPolicies', 1)->count()],
            ['name' => 'Number of Completed master theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'PhD degree')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed Ph.D. theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Master o equivalent')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed undergraduate theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Undergraduate degree or profesional title')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Postdoc working the center', 'value' => postDoc::where('progressReport', $progressReport)->whereNotNull('endingYear')->where('endingYear', '>=', now()->year)->count()],
            ['name' => 'Number of Visiting researchers', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('collaborationStay')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where('collaborationStay', 'Long Visit (more than two weeks)')
                ->where('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%')
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers from other centers or projects', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers to other institutions', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'NOT LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of WoS Publications coauthored with international researchers', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('internationalExternalResearchers')->where('internationalExternalResearchers', 1)->count()],
            ['name' => 'Number of WoS Publications written in co-authorship with national researchers from other institutions', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('nationalExternalResearchers')->where('nationalExternalResearchers', 1)->count()],
            ['name' => 'Number of Participations in projects led by other research teams', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')->where('moduleType', 0)->where('activityName', 'LIKE', '%Participation in R&D Projects directed by other Researcher (external)%')->count()],
            ['name' => 'Number of outreach and/or extension activities', 'value' => outreachActivities::where('progressReport', $progressReport)->count()],
            ['name' => 'Total number of people attending at dissemination and/or outreach activities(workshops,talks,seminars)', 'value' => outreachActivities::where('progressReport', $progressReport)->sum('attendantsAmount')]
        ];
    
        return response()->json($data);
    }

    protected function getModuleClass($moduleName)
    {
        $modules = [
            'isiPublications' => isiPublication::class,
            'nonIsiPublications' => nonIsiPublication::class,
            'books' => books::class,
            'awards' => awards::class,
            'organizationsScEvents' => organizationsScEvents::class,
            'participationScEvents' => participationScEvents::class,
            'scCollaborations' => scCollaborations::class,
            'thesisStudents' => thesisStudent::class,
            'postDocs' => postDoc::class,
            'outreachActivities' => outreachActivities::class,
            'patents' => patents::class,
            'publicPrivate' => publicPrivate::class,
            'technologyKnowledge' => technologyKnowledge::class,
            'fundingSources' => fundingSources::class,
        ];

        if (!isset($modules[$moduleName])) {
            throw new \Exception("Module {$moduleName} is not defined.");
        }

        return $modules[$moduleName];
    }

}
