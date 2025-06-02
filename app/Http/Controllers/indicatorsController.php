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
use App\Models\ProgressReportGoals;
use App\Models\publicPrivate;
use App\Models\researchLines;
use App\Models\scCollaborations;
use App\Models\technologyKnowledge;
use App\Models\thesisStudent;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class indicatorsController extends Controller
{

    public function getFilteredRecordsByModule(Request $request)
    {
        try {
            $moduleName = $request->input('module');
            $lineId = $request->input('line');
            $personId = $request->input('person');
            $roleUser = $request->input('role');
    
            $lineName = null;
            $filteredUsers = [];
            $goals = [];
    
            // Obtener el nombre de la línea si se recibe un ID
            if ($lineId) {
                $researchLine = researchLines::find($lineId);
                if (!$researchLine) {
                    return response()->json(['error' => 'Research line not found'], 404);
                }
                $lineName = strtolower($researchLine->name);
            
                // Obtener usuarios de esta línea de investigación
                $filteredUsers = User::where('idResearchLine', $lineId)
                                     ->where('estado', 1)
                                     ->whereIn('idRole', [1, 2, 3])  // Filtrar por idRole 1, 2 o 3
                                     ->select('id', 'name', 'idRole')
                                     ->get();
            }
            
            // Obtener los usuarios por tipo de investigador
            if ($roleUser) {
                // Si se pasa un valor en roleUser, obtener usuarios con ese idRole
                $filteredUsers = User::where('idRole', $roleUser)
                                     ->where('estado', 1)
                                     ->select('id', 'name','idRole')
                                     ->get();
            }
            
    
            // 📌 Obtener los goals correspondientes al filtro seleccionado
            if ($lineId) {
                // Paso 1: Obtener todos los usuarios de la línea de investigación
                $usersInLine = User::where('idResearchLine', $lineId)->where('estado',1)
                ->whereIn('idRole', [1, 2, 3]) // Solo considerar los roles con id entre 1 y 3
                ->get();
            
                // Paso 2: Inicializar un array para almacenar las metas totales por año
                $totalGoals = [];
        
                // Paso 3: Iterar sobre cada usuario de la línea de investigación
                foreach ($usersInLine as $user) {
                    // Paso 4: Obtener el tipo de investigador (idRole) del usuario
                    $roleId = $user->idRole;

                    // Paso 5: Solo considerar roles de 1 a 3 (según lo indicado)
                    if ($roleId >= 1 && $roleId <= 3) {
                        // Obtener el progreso de metas para este tipo de investigador (idRole)
                        $query = ProgressReportGoals::where('researcher_type_id', $roleId);

                        if ($moduleName) {
                            $query->where('module', $moduleName); // Filtro por módulo
                        }else{
                            $query->where('module', null);
                        }
                        
                        // Obtener el progreso de metas para este tipo de investigador y módulo (si se aplica)
                        $progressReport = $query->first();
                        if ($progressReport) {
                            // Extraer las metas de este tipo de investigador
                            $goals = json_decode($progressReport->goals, true);

                            // Paso 6: Dividir las metas entre el número de usuarios con ese idRole
                            $usersWithType = User::where('idRole', $roleId)->get();
                            $totalUsers = $usersWithType->count();

                            // Paso 8: Sumar las metas de este usuario a las metas totales de la línea
                            foreach ($goals as $year => $goal) {
                                if (!isset($totalGoals[$year])) {
                                    $totalGoals[$year] = 0;
                                }
                                $totalGoals[$year] += $goal; // Sumar las metas para cada año
                            }
                        }
                    }
                }

                // Paso 9: Ordenar los goles por año
                ksort($totalGoals);
                
                // Solo asignar los goles si progressReport no es nulo
                if ($progressReport && !empty($totalGoals)) {
                    $progressReport->goals = json_encode($totalGoals);
                }


                
            }elseif ($personId) {
                // 1. Obtener el idRole del usuario (personId)
                $user = User::find($personId);
                if (!$user) {
                    return response()->json([
                        'message' => 'User not found',
                        'data' => null
                    ], 404);
                }
        
                $idRole = $user->idRole;
        
                // 2. Buscar el progress report para ese researcher_type_id (idRole)
                $query = ProgressReportGoals::where('researcher_type_id', $idRole);

                if ($moduleName) {
                    $query->where('module', $moduleName); // Filtro por módulo
                }else{
                    $query->where('module', null);
                }
    
                // Obtener el progreso de metas para este tipo de investigador y módulo (si se aplica)
                $progressReport = $query->first();
                
                if ($progressReport) {
                    // 3. Obtener todos los usuarios con ese researcher_type_id
                    $usersWithType = User::where('idRole', $idRole)->get();
                    $totalUsers = $usersWithType->count();
        
                    // 4. Dividir los goals entre el número de usuarios con ese idRole
                    $goals = json_decode($progressReport->goals, true);
        
                    // Actualizar el progress report
                    $progressReport->goals = json_encode($goals);
                }

            } elseif ($roleUser) {
                $query = ProgressReportGoals::where('researcher_type_id', $roleUser);

                if ($moduleName) {
                    $query->where('module', $moduleName); // Filtro por módulo
                } else {
                    $query->where('module', null);
                }
            
                // Obtener el progreso de metas para este tipo de investigador y módulo (si se aplica)
                $progressReport = $query->first();
                
                if ($progressReport) {
                    // Contar la cantidad de usuarios activos con el idRole dado
                    $activeUsersCount = User::where('idRole', $roleUser)->where('estado', 1)->count();
                    
                    // Decodificar los goals (asumidos en formato JSON)
                    $goalsArray = json_decode($progressReport->goals, true);
                    
                    // Multiplicar cada goal por la cantidad de usuarios activos
                    foreach ($goalsArray as $year => $goal) {
                        $goalsArray[$year] = $goal * $activeUsersCount;
                    }
                    
                    // Actualizar los goals en el progress report
                    $progressReport->goals = json_encode($goalsArray);
                    $goals = $goalsArray;
                }
            } else {
                $progressReport = null;
            }
    
            // Convertir goals almacenados en JSON si existen
            if ($progressReport && $progressReport->goals) {
                $goals = json_decode($progressReport->goals, true);
            }

            // Lista de modelos disponibles
            $models = [
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
    
            // Si se seleccionó un módulo, usar solo ese
            if ($moduleName && isset($models[$moduleName])) {
                $models = [$moduleName => $models[$moduleName]];
            }
            
            $recordsByYear = [];

            foreach ($models as $modelName => $modelClass) {
                $model = new $modelClass;
                $query = $model->select('progressReport', DB::raw('COUNT(*) as total'));
                $userColumn = ($modelName === 'books') ? 'centerResearcher' : 'idUsuario';
    
                // Filtrar por usuario
                if ($personId) {
                    $user = User::find($personId);
                    if (!$user) {
                        return response()->json(['error' => 'User not found'], 404);
                    }
                    $normalizedUserName = strtolower($user->name);
                    $query->where(function ($query) use ($personId, $normalizedUserName, $userColumn) {
                        $query->where($userColumn, $personId)
                              ->orWhereRaw('LOWER(researcherInvolved) LIKE ?', ["%{$normalizedUserName}%"]);
                    });
                }
    
                // Filtrar por línea de investigación
                if ($lineName) {
                    $query->whereRaw('LOWER(researchLinesInvolved) LIKE ?', ["%{$lineName}%"]);
                }
    
                // Filtrar por tipo de investigador
                if ($roleUser) {
                    $researchers = User::where('idRole', $roleUser)->where('estado',1)->pluck('id');
                    if ($researchers->isNotEmpty()) {
                        $query->whereIn($userColumn, $researchers);
                    }
                }
    
                $query->where('status', 'Finished');

                // Agrupar por progressReport
                $groupedRecords = $query->groupBy('progressReport')
                                        ->orderBy('progressReport', 'asc')
                                        ->get();
    
                // Sumar los valores por año
                foreach ($groupedRecords as $record) {
                    $progressReportYear = $record->progressReport;
    
                    if (!isset($recordsByYear[$progressReportYear])) {
                        $recordsByYear[$progressReportYear] = 0;
                    }
    
                    $recordsByYear[$progressReportYear] += $record->total;
                }
            }
    
            // Ordenar los resultados por año de progressReport
            ksort($recordsByYear);
            // Obtener el progressReport actual desde extraTables
            $currentProgressReport = extraTables::where('name', 'progressReport')->value('value');
            
            $indicatorsValue = [];

             if ($moduleName) {
                 switch ($moduleName) {
                     case 'isiPublications':
                         if ($personId) {
                             $value = $recordsByYear[$currentProgressReport] ?? null;
                             $indicatorsValue = [
                                 'indicator' => 'Number of WoS on year ' . $currentProgressReport,
                                 'value' => $value
                                ];
                         }else if($roleUser){
                             $usersWithRole = User::where('idRole', $roleUser)->where('estado',1)->get();

                             if ($usersWithRole->count() > 0) {
                                 $userCount = $usersWithRole->count();

                                 $value = floor($recordsByYear[10]/$userCount) ?? null;
                                 $indicatorsValue = [
                                     'indicator' => 'Average WoS Publications per Person on Year ' . $currentProgressReport,
                                     'value' => $value
                                 ];
                             } else {
                                 $indicatorsValue = [
                                     'indicator' => 'Average WoS Publications per Person on Year ' . $currentProgressReport,
                                     'value' => null
                                 ];
                             }
                          }else if ($lineId) {
                             $usersWithResearchLine = User::where('idResearchLine', $lineId)->where('estado',1)->whereIn('idRole', [1, 2, 3]) ->get();
                        
                             if ($usersWithResearchLine->count() > 0) {
                                 $userCount = $usersWithResearchLine->count();
                        
                                 $value = floor($recordsByYear[10]/$userCount) ?? null;
                        
                                 $indicatorsValue = [
                                     'indicator' => 'Average WoS Publications per Person on Year ' . $currentProgressReport,
                                     'value' => $value
                                 ];
                             } else {
                                 $indicatorsValue = [
                                     'indicator' => 'Average WoS Publications per Person on Year ' . $currentProgressReport,
                                     'value' => null
                                 ];
                             }
                         }
                     break;
        
                     case 'nonIsiPublications':
                             $value = $recordsByYear[$currentProgressReport] ?? null;
                            
                            $indicatorsValue = [
                                'indicator' => 'Total Non-WoS Publications on Year ' . $currentProgressReport,
                                'value' => $value
                            ];

                         break;
        
                     case 'books':
                         $value = $recordsByYear[$currentProgressReport] ?? null;
                        
                         $indicatorsValue = [
                             'indicator' => 'Total Books Publications on Year ' . $currentProgressReport,
                             'value' => $value
                         ];
                     break;
        
                     case 'awards':
                         $value = $recordsByYear[$currentProgressReport] ?? null;
                        
                         $indicatorsValue = [
                             'indicator' => 'Total Awards Publications on Year ' . $currentProgressReport,
                             'value' => $value
                         ];
                     break;
        
                     case 'organizationsScEvents':
                         $value = $recordsByYear[$currentProgressReport] ?? null;
                        
                         $indicatorsValue = [
                             'indicator' => 'Total Organization Sc Events Publications on Year  ' . $currentProgressReport,
                             'value' => $value
                         ];
                     break;
        
                     case 'participationScEvents':
                         $value = $recordsByYear[$currentProgressReport] ?? null;
                        
                         $indicatorsValue = [
                             'indicator' => 'Total Participation Sc Events Publications on Year  ' . $currentProgressReport,
                             'value' => $value
                         ];
                     break;

                     case 'scCollaborations':
                         $value = $recordsByYear[$currentProgressReport] ?? null;
                        
                         $indicatorsValue = [
                             'indicator' => 'Total Scientific Collaborations Publications on Year ' . $currentProgressReport,
                             'value' => $value
                         ];
                     break;

                     case 'thesisStudents':
                        $query = thesisStudent::query()->where('progressReport', $currentProgressReport)->where('thesisStatus',1);

                        if ($personId) {
                            $query->where('idUsuario', $personId);
                        } elseif ($roleUser) {
                            $users = User::where('idRole', $roleUser)->where('estado', 1)->pluck('id');
                            $query->whereIn('idUsuario', $users);
                        } elseif ($lineId) {
                            $users = User::where('idResearchLine', $lineId)
                                        ->where('estado', 1)
                                        ->whereIn('idRole', [1, 2, 3])
                                        ->pluck('id');
                            $query->whereIn('idUsuario', $users);
                        }

                        // Obtener conteos por tipo de academicDegree
                        $thesisCounts = $query->select('academicDegree')
                            ->selectRaw('COUNT(*) as total')
                            ->groupBy('academicDegree')
                            ->pluck('total', 'academicDegree');

                        // Asegurar que los 3 tipos estén presentes aunque no existan en la DB
                        $types = [
                            'Undergraduate degree or profesional title',
                            'Master o equivalent',
                            'PhD degree'
                        ];

                        $indicatorsValue = [];

                        foreach ($types as $type) {
                            $indicatorsValue[] = [
                                'indicator' => "Total {$type} Theses on Progress Report {$currentProgressReport}",
                                'value' => $thesisCounts[$type] ?? 0
                            ];
                        }

                         break;

                     case 'postDocs':
                         $value = $recordsByYear[$currentProgressReport] ?? null;
                        
                         $indicatorsValue = [
                             'indicator' => 'Total Postdoctoral Fellows Publications on Year ' . $currentProgressReport,
                             'value' => $value
                         ];
                         break;

                    case 'outreachActivities':
                        $query = outreachActivities::where('progressReport', $currentProgressReport);

                        if ($personId) {
                            $query->where('idUsuario', $personId);
                        } elseif ($roleUser) {
                            $users = User::where('idRole', $roleUser)->where('estado', 1)->pluck('id');
                            $query->whereIn('idUsuario', $users);
                        } elseif ($lineId) {
                            $users = User::where('idResearchLine', $lineId)
                                        ->where('estado', 1)
                                        ->whereIn('idRole', [1, 2, 3])
                                        ->pluck('id');
                            $query->whereIn('idUsuario', $users);
                        }

                        $activityCount = $query->count();
                        $totalAttendants = $query->sum('attendantsAmount');

                        $indicatorsValue[] = [
                            'indicator' => 'Total Outreach Activities on Year ' . $currentProgressReport,
                            'value' => $activityCount
                        ];
                        $indicatorsValue[] = [
                            'indicator' => 'Total Attendants in Outreach Activities on Year ' . $currentProgressReport,
                            'value' => $totalAttendants
                        ];

                        break;
                    
                    case 'patents':
                        $query = patents::where('progressReport', $currentProgressReport)
                                    ->where('applicationStatus', 'Approved');

                        if ($personId) {
                            $query->where('idUsuario', $personId);
                        } elseif ($roleUser) {
                            $users = User::where('idRole', $roleUser)->where('estado', 1)->pluck('id');
                            $query->whereIn('idUsuario', $users);
                        } elseif ($lineId) {
                            $users = User::where('idResearchLine', $lineId)
                                        ->where('estado', 1)
                                        ->whereIn('idRole', [1, 2, 3])
                                        ->pluck('id');
                            $query->whereIn('idUsuario', $users);
                        }

                        $approvedCount = $query->count();

                        $indicatorsValue[] = [
                            'indicator' => 'Total Approved Patents on Year ' . $currentProgressReport,
                            'value' => $approvedCount
                        ];
                        break;

                    
                        case 'publicPrivate':
                            $query = publicPrivate::where('progressReport', $currentProgressReport);

                            if ($personId) {
                                $query->where('idUsuario', $personId);
                            } elseif ($roleUser) {
                                $users = User::where('idRole', $roleUser)->where('estado', 1)->pluck('id');
                                $query->whereIn('idUsuario', $users);
                            } elseif ($lineId) {
                                $users = User::where('idResearchLine', $lineId)
                                            ->where('estado', 1)
                                            ->whereIn('idRole', [1, 2, 3])
                                            ->pluck('id');
                                $query->whereIn('idUsuario', $users);
                            }

                            $totalCount = $query->count();
                            $publicPolicyCount = (clone $query)->where('participationPublicPolicies', 1)->count();

                            $indicatorsValue[] = [
                                'indicator' => 'Total Public-Private Events on Year ' . $currentProgressReport,
                                'value' => $totalCount
                            ];
                            $indicatorsValue[] = [
                                'indicator' => 'Events Involving Public Policy Participation on Year ' . $currentProgressReport,
                                'value' => $publicPolicyCount
                            ];

                            break;

                        case 'technologyKnowledge':
                            $query = technologyKnowledge::where('progressReport', $currentProgressReport);

                            if ($personId) {
                                $query->where('idUsuario', $personId);
                            } elseif ($roleUser) {
                                $users = User::where('idRole', $roleUser)->where('estado', 1)->pluck('id');
                                $query->whereIn('idUsuario', $users);
                            } elseif ($lineId) {
                                $users = User::where('idResearchLine', $lineId)
                                            ->where('estado', 1)
                                            ->whereIn('idRole', [1, 2, 3])
                                            ->pluck('id');
                                $query->whereIn('idUsuario', $users);
                            }

                            $baseQuery = clone $query;

                            $techTransferCount = (clone $baseQuery)->where('technologyTransfer', true)->count();
                            $knowledgeTransferCount = (clone $baseQuery)->where('knowledgeTransfer', true)->count();

                            $trlLow = (clone $baseQuery)->whereBetween('actualTrl', [1, 4])->count();
                            $trlMid = (clone $baseQuery)->whereBetween('actualTrl', [5, 6])->count();
                            $trlHigh = (clone $baseQuery)->whereBetween('actualTrl', [7, 9])->count();

                            $indicatorsValue[] = [
                                'indicator' => 'Technology Transfer Records on Report ' . $currentProgressReport,
                                'value' => $techTransferCount
                            ];
                            $indicatorsValue[] = [
                                'indicator' => 'Knowledge Transfer Records on Report ' . $currentProgressReport,
                                'value' => $knowledgeTransferCount
                            ];
                            $indicatorsValue[] = [
                                'indicator' => 'TRL 1-4 Records on Report ' . $currentProgressReport,
                                'value' => $trlLow
                            ];
                            $indicatorsValue[] = [
                                'indicator' => 'TRL 5-6 Records on Report ' . $currentProgressReport,
                                'value' => $trlMid
                            ];
                            $indicatorsValue[] = [
                                'indicator' => 'TRL 7-9 Records on Report ' . $currentProgressReport,
                                'value' => $trlHigh
                            ];

                            break;

                        case 'fundingSources':
                            $query = fundingSources::where('progressReport', $currentProgressReport);

                            if ($personId) {
                                $query->where('idUsuario', $personId);
                            } elseif ($roleUser) {
                                $users = User::where('idRole', $roleUser)->where('estado', 1)->pluck('id');
                                $query->whereIn('idUsuario', $users);
                            } elseif ($lineId) {
                                $users = User::where('idResearchLine', $lineId)
                                            ->where('estado', 1)
                                            ->whereIn('idRole', [1, 2, 3])
                                            ->pluck('id');
                                $query->whereIn('idUsuario', $users);
                            }

                            $types = ['Public funds', 'Productive/Private funds', 'International funds'];

                            foreach ($types as $type) {
                                $count = (clone $query)->where('typeSources', $type)->count();

                                $indicatorsValue[] = [
                                    'indicator' => "Total {$type} on Progress Report {$currentProgressReport}",
                                    'value' => $count
                                ];
                            }
                            break;
                  
                 }
             }
            return response()->json([
                'currentProgressReport' => $currentProgressReport,
                'recordsByYear' => $recordsByYear,
                'filteredUsers' => $filteredUsers, // Lista de usuarios filtrados
                'goals' => $goals ?? [],
                'indicators' => $indicatorsValue,
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
