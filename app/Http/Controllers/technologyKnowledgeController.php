<?php

namespace App\Http\Controllers;

use App\Models\SessionLog;
use App\Models\technologyKnowledge;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class technologyKnowledgeController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();

        // Obtener los nombres de los investigadores relacionados
        if (isset($input['researcherInvolved'])) {
            $researcherNames = explode(',', $input['researcherInvolved']);

            // Limpiar y normalizar los nombres
            $normalizedNames = array_map('trim', $researcherNames);

            // Obtener las líneas de investigación para cada investigador
            $researchLines = [];
            foreach ($normalizedNames as $name) {
                $user = User::where('name', 'LIKE', '%' . $name . '%')->first();
                if ($user && $user->researchLine) {
                    $researchLines[] = $user->researchLine->name;
                }
            }

            // Asignar las líneas de investigación al campo antes de guardar
            $input['researchLinesInvolved'] = implode(', ', array_unique($researchLines));
        }

        $technologyKnowledge = technologyKnowledge::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyTechnology(Request $request)
    {
        $query = technologyKnowledge::where('description', $request['description'])
            ->whereNotNull('description')
            ->where('nameOfInstitutionInvolved', $request['nameOfInstitutionInvolved'])
            ->whereNotNull('nameOfInstitutionInvolved')
            ->where('year', $request['year'])
            ->whereNotNull('year');
    
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function technologyKnowledgesActive($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        $today = Carbon::today();  // No es necesario formatear aún, trabajamos con objetos Carbon directamente
    
        // Obtener usuario y sus roles
        $user = User::where('id', $userID)->with('roles')->first();
    
        // Identificar roles del usuario
        if ($user->roles->isEmpty()) {
            $roles[] = '';
        } else {
            foreach ($user->roles as $rol) {
                if (in_array($rol['name'], ['Administrator', 'Anid', 'Staff'])) {
                    $roles[] = $rol['name'];
                    $administrador = true;
                } elseif ($rol['name'] == 'Titular Researcher') {
                    $roles[] = $rol['name'];
                    $titularResearcher = true;
                }
            }
        }
    
        // Normalizar nombre del usuario
        function normalizeString1($string) {
            $string = strtolower($string);
            $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
            $string = preg_replace('/[^a-z0-9\s]/', '', $string);
            return trim($string);
        }
    
        $userName = normalizeString1($user->name);
        if ($userName == 'wael elderedy') {
            $userName = 'wael';
        }
    
        // Lógica para Administrador
        if ($administrador) {
            $technologyKnowledge = technologyKnowledge::with('usuario')
                ->get();
        } elseif ($titularResearcher) {
            $userResearchLine = $user->idResearchLine;
    
            $technologyKnowledge = technologyKnowledge::where(function($query) use ($userResearchLine, $userID, $userName) {
                $query->where('idUsuario', $userID)  // Tecnologías propias
                      ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%")  // Tecnologías donde participa
                      ->orWhereHas('usuario', function($subQuery) use ($userResearchLine) {  // Tecnologías de otros usuarios de la misma línea
                          $subQuery->where('idResearchLine', $userResearchLine);
                      });
            })
            ->with('usuario')
            ->get();
        } else {
            $technologyKnowledge = technologyKnowledge::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        $technologyKnowledge = $technologyKnowledge->filter(function($technology) use ($today) {
            // Verificar que yearEnding y monthEnding existan
            if ($technology->yearEnding && $technology->monthEnding) {
                // Convertir el nombre del mes a número usando Carbon
                try {
                    $monthNumber = Carbon::parse($technology->monthEnding)->month;
                    $endingDate = Carbon::create($technology->yearEnding, $monthNumber, 1);
                    
                    // Comparar la fecha construida con la fecha actual
                    return $endingDate->greaterThan($today);
                } catch (\Exception $e) {
                    // Si ocurre un error al parsear la fecha, se omite este registro
                    return false;
                }
            }
            return false;  // Si faltan datos, no considerar el registro como activo
        });
    
        return response()->json($technologyKnowledge->values());
    }

    public function show($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        
        $user = User::where('id', $userID)->with('roles')->first();
        $technologyKnowledge = technologyKnowledge::where('idUsuario', $userID)->with('usuario')->get();
    
        // Identificar roles del usuario
        if ($user->roles->isEmpty()) {
            $roles[] = '';
        } else {
            foreach ($user->roles as $rol) {
                if ($rol['name'] == 'Administrator' || $rol['name'] == 'Anid' || $rol['name'] == 'Staff') {
                    $roles[] = $rol['name'];
                    $administrador = true;
                } elseif ($rol['name'] == 'Titular Researcher') {
                    $roles[] = $rol['name'];
                    $titularResearcher = true;
                }
            }
        }
    
        function normalizeString($string) {
            $string = strtolower($string);
            $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
            $string = preg_replace('/[^a-z0-9\s]/', '', $string);
            return trim($string);
        }
    
        $userName = normalizeString($user->name);
        if ($userName == 'wael elderedy') {
            $userName = 'wael';
        }
    
        // Lógica para Administrador
        if ($administrador) {
            $technologyKnowledge = technologyKnowledge::with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $technologyKnowledge = technologyKnowledge::where('moduleType', 0)
                ->where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)  // Tecnologías propias
                        ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%")  // Tecnologías donde participa
                        ->orWhereHas('usuario', function($subQuery) use ($userResearchLine) {  // Tecnologías de otros usuarios de la misma línea
                            $subQuery->where('idResearchLine', $userResearchLine);
                        });
                })
                ->with('usuario')
            ->get();
            return $technologyKnowledge;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $technologyKnowledge = technologyKnowledge::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        return $technologyKnowledge;
    }
    

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $technologyKnowledge = technologyKnowledge::find($id);
        $input = $request->all();

        // Obtener los nombres de los investigadores relacionados
        if (isset($input['researcherInvolved'])) {
            $researcherNames = explode(',', $input['researcherInvolved']);

            // Limpiar y normalizar los nombres
            $normalizedNames = array_map('trim', $researcherNames);

            // Obtener las líneas de investigación para cada investigador
            $researchLines = [];
            foreach ($normalizedNames as $name) {
                $user = User::where('name', 'LIKE', '%' . $name . '%')->first();
                if ($user && $user->researchLine) {
                    $researchLines[] = $user->researchLine->name;
                }
            }

            // Asignar las líneas de investigación al campo antes de guardar
            $input['researchLinesInvolved'] = implode(', ', array_unique($researchLines));
        }

        $technologyKnowledge->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Technology Knowledge",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importTecAndKnow(Request $request)
    {
        $data = $request->input('data');

        
        foreach ($data as $rowData) {
            $categoryOfTransfer = $rowData['Category of Transfer'];

            $technologyTransfer = strpos($categoryOfTransfer, '1') !== 0;
            $knowledgeTransfer = strpos($categoryOfTransfer, '2') !== 0;
            $typeOfTransferMapping = [
                "1" => "Spin-off",
                "2" => "Licenses",
                "3" => "Technology Transfer Agreement",
                "4" => "Services",
                "5" => "Sale of technology transfer product",
                "6" => "Other",
            ];
            
            $typeOfTransferMapping = [
                "1" => "Spin-off",
                "2" => "Licenses",
                "3" => "Technology Transfer Agreement",
                "4" => "Services",
                "5" => "Sale of technology transfer product",
                "6" => "Other",
            ];
            
            // Verifica si el valor está presente en el mapeado
            $typeOfTransfer = isset($typeOfTransferMapping[$rowData['Type of Transfer']]) ? $typeOfTransferMapping[$rowData['Type of Transfer']] : "";
            
            $technologyKnowledge = technologyKnowledge::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'technologyTransfer' => $technologyTransfer,
                'knowledgeTransfer' => $knowledgeTransfer,
                'typeOfTransfer' => $typeOfTransfer,
                'nameOfBeneficiary' => $rowData['Name of Beneficiary'],
                'country' => $rowData['Country'],
                'city' => $rowData['City'],
                'placeRegion' => $rowData['Place Region'],
                'year' => $rowData['Year'],
                'nameOfResearch' => $rowData['Number of Research Line'],
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
                'description' => $rowData['Description'],
                'nameOfInstitutionInvolved' => $rowData['Name of the Institions Involved'],
                'researcherInvolved' => $rowData['Researchers Involved'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }


     // Función para eliminar un registro
     public function destroy(Request $request, $id)
     {
         $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
         $technologyKnowledge = technologyKnowledge::find($id);
     
         if (!$technologyKnowledge) {
             return response()->json(['message' => 'technologyKnowledge not found'], 404);
         }
     
         // Elimina el libro
         $technologyKnowledge->delete();
     
         // Registra el evento en el log
         SessionLog::create([
             'user_id' => $userId,
             'event_type' => 'delete',
             'description' => "Usuario eliminó un registro con ID {$id} en el módulo Technology Knowledge",
             'timestamp' => Carbon::now(),
             'ip_address' => $request->ip(),
         ]);
     
         return response()->json(['message' => 'technologyKnowledge successfully deleted']);
     }
}
