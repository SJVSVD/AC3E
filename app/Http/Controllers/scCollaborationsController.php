<?php

namespace App\Http\Controllers;
use App\Models\scCollaborations;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class scCollaborationsController extends Controller
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
        $scCollaborations = scCollaborations::create($input);
        return response()->json("Colaboracion Creada!");
    }

    // Función para detectar registros duplicados
     public function verifyCollaboration(Request $request)
    {
        $query = scCollaborations::where('moduleType', 0)
            ->where('nameOfExternalResearcher', $request['nameOfExternalResearcher'])
            ->where('beginningDate', $request['beginningDate'])
            ->whereNotNull('beginningDate')
            ->whereNotNull('nameOfExternalResearcher');
    
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function show($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        
        // Obtener usuario y sus roles
        $user = User::where('id', $userID)->with('roles')->first();
        $scCollaborations = scCollaborations::where('idUsuario', $userID)->where('moduleType', 0)->with('usuario')->get();
    
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
    
        // Normalizar nombre del usuario
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
            $scCollaborations = scCollaborations::where('moduleType', 0)
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
            
            $scCollaborations = scCollaborations::where('moduleType', 0)
                ->where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                        ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->with('usuario')
                ->get();
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $scCollaborations = scCollaborations::where('moduleType', 0)
                ->where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                        ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        $scCollaborations = $scCollaborations->filter(function($collaboration){
            return $collaboration->moduleType == 0;
        });
    
    
        return $scCollaborations;
    }
    

    public function scCollaborationsActive($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        $today = Carbon::today()->format('Y-m-d');  // Formatear `$today` para comparar correctamente con `endingDate`
    
        // Obtener usuario y sus roles
        $user = User::where('id', $userID)->with('roles')->first();
    
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
            $scCollaborations = scCollaborations::where('moduleType', 0)
                ->whereDate('endingDate', '>', $today) // Usar `whereDate` para comparar correctamente
                ->with('usuario')
                ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $scCollaborations = scCollaborations::where('moduleType', 0)
                ->whereDate('endingDate', '>', $today) // Usar `whereDate` para comparar correctamente
                ->where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                        ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->with('usuario')
                ->get();

        } else {
            // Lógica estándar para usuarios sin roles especiales
            $scCollaborations = scCollaborations::where('moduleType', 0)
                ->whereDate('endingDate', '>', $today) // Usar `whereDate` para comparar correctamente
                ->where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                        ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        $scCollaborations = $scCollaborations->filter(function($collaboration) use ($today) {
            return $collaboration->moduleType == 0 && 
                   Carbon::parse($collaboration->endingDate)->greaterThan(Carbon::parse($today)); // Asegurar comparación de fechas y moduleType
        });
    
        return $scCollaborations;
    }
    
    
    
    // Función para importar los registros que vienen desde excel
    public function importCollaboration(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {

            $activityNameMapping = [
                "1" => "Visit in Chile (include students)",
                "2" => "Visit abroad (include students)",
                "3" => "Research Stay (Pasantia de investigacion) (include students)",
                "4" => "Participation in R&D Projects directed by other Researcher (external)",
                "5" => "Participation in R&D Projects directed by an AC3E Researcher",
                "6" => "Other",
            ];
            
            $activityName = '';
            $moduleType = 0;
            // Verificar si el campo 'Type of Event' está presente
            if (isset($rowData['Activity Name'])) {
                // Separar el campo 'Type of Event' por comas
                $events = explode(',', $rowData['Activity Name']);
            
                // Iterar sobre cada evento
                foreach ($events as &$event) {
                    // Eliminar espacios en blanco alrededor del evento
                    $event = trim($event);
            
                    // Reemplazar el 6 seguido de un espacio y paréntesis por 'Other'
                    $event = preg_replace('/\b6\s*\(/', 'Other (', $event);
            
                    // Verificar si el evento es un número válido en el mapeo
                    if (isset($activityNameMapping[$event])) {
                        // Si es un número válido, asignar el valor correspondiente del mapeo
                        $event = $activityNameMapping[$event];
                        
                        // Verificar si el evento corresponde a los números 4 o 5
                        if ($event === $activityNameMapping["4"] || $event === $activityNameMapping["5"]) {
                            // Actualizar el moduleType a 1
                            $moduleType = 1;
                        }
                    } elseif ($event === '6') {
                        // Si el evento es '6', asignar el valor 'Other'
                        $event = 'Other';
                    }
                }
            
                // Unir los eventos nuevamente en una cadena separada por comas
                $activityName = implode(', ', $events);
            
                // Agregar un punto al final de la cadena si no está presente
                if (!empty($activityName)) {
                    $activityName .= '.';
                }
            }          

            $collaborationStayMapping = [
                "1" => "Short Visit (up to two weeks)",
                "2" => "Long Visit (more than two weeks)",
                "3" => "Other",
            ];
            
            $otherStay = 0;
            
            // Verificar si el campo 'Collaboration stay' está presente
            if (isset($rowData['Collaboration stay'])) {
                // Obtener el valor del campo 'Collaboration stay'
                $collaborationStayValue = $rowData['Collaboration stay'];
            
                // Verificar si el valor es 1 o 2, de lo contrario, asignar "Other"
                if (in_array($collaborationStayValue, ['1', '2'])) {
                    $collaborationStay = $collaborationStayMapping[$collaborationStayValue];
                    $otherStay = 0; // Establecer $otherStay como 0 cuando el valor no es 3
                } else {
                    $collaborationStay = $collaborationStayMapping['3'];
                    $otherStay = 1; // Establecer $otherStay como 1 cuando el valor es 3
                }
            } else {
                // Si el campo 'Collaboration stay' no está presente, asignar una cadena vacía o un valor predeterminado según lo necesites
                $collaborationStay = null; // O cualquier valor predeterminado
                $otherStay = 0; // O cualquier valor predeterminado
            }

            $studentOrResearcher = $rowData['External researcher is'];
            if($studentOrResearcher == 1){
                $studentOrResearcher = 'Researcher';
            }else{
                $studentOrResearcher = 'Student';
            }
            $internationalOrNational = $rowData['Colaboracion internacional'];
            if($internationalOrNational == 1){
                $internationalOrNational = 'International';
            }else{
                $internationalOrNational = 'National';
            }
            $cityCountry = explode(', ', $rowData['Country/City of destination']);

            // Verificar si el formato es distinto al esperado
            if (count($cityCountry) === 2) {
                // Asignar la ciudad y el país a las variables correspondientes
                $city = $cityCountry[1];
                $country = $cityCountry[0];
            } else {
                // Asignar "Global" a ambas variables si el formato es diferente
                $city = 'Global';
                $country = 'Global';
            }


            
            $scCollaborations = scCollaborations::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'moduleType' => $moduleType,
                'institutionCollaborates' => $rowData['Institution with which the Center collaborates'],
                'researcherInvolved' => $rowData['Name of AC3E member'],
                'studentOrResearcher' => $studentOrResearcher,
                'activityType' => $internationalOrNational,
                'activityName' => $activityName,
                'countryOrigin' => $rowData['Country Origin'],
                'cityOrigin' => $rowData['City Origin'],
                'countryDestination' => $country,
                'cityDestination' => $city,
                'beginningDate' => $rowData['Beginning Date'],
                'endingDate' => $rowData['Ending Date'],
                'progressReport' => $rowData['Progress Report'],
                'nameOfAC3EMember' => $rowData['Name of AC3E member'],
                'nameOfExternalResearcher' => $rowData['Name of external Researchers Involved'],
                'collaborationStay' => $collaborationStay,
                'otherStay' => $otherStay,
                'comments' => $rowData['Comentarios']
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $scCollaborations = scCollaborations::find($id);
        $input = $request->all();
        $scCollaborations->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Sc Collaborations",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json($input);
    }


     // Función para eliminar un registro
     public function destroy(Request $request, $id)
     {
         $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
         $scCollaborations = scCollaborations::find($id);
     
         if (!$scCollaborations) {
             return response()->json(['message' => 'scCollaborations not found'], 404);
         }
     
         // Elimina el libro
         $scCollaborations->delete();
     
         // Registra el evento en el log
         SessionLog::create([
             'user_id' => $userId,
             'event_type' => 'delete',
             'description' => "Usuario eliminó un registro con ID {$id} en el módulo Sc Collaborations",
             'timestamp' => Carbon::now(),
             'ip_address' => $request->ip(),
         ]);
     
         return response()->json(['message' => 'scCollaborations successfully deleted']);
     }
}
