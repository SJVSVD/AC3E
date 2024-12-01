<?php

namespace App\Http\Controllers;
use App\Models\outreachActivities;
use App\Models\User;
use Illuminate\Http\Request;

class outreachActivitiesController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        $outreachActivities = outreachActivities::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyOutreach(Request $request)
    {
        $query = outreachActivities::where('activityName', $request['activityName'])
            ->where('date', $request['date'])
            ->where('country', $request['country'])
            ->whereNotNull('activityName')
            ->whereNotNull('date')
            ->whereNotNull('country');
    
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
        $outreachActivities = outreachActivities::where('idUsuario', $userID)->with('usuario')->get();
        
        $user = User::where('id', $userID)->with('roles')->first();
    
        // Identificar roles del usuario
        if ($user->roles->isEmpty()) {
            $roles[] = '';
        } else {
            foreach ($user->roles as $rol) {
                if ($rol['name'] == 'Administrator') {
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
            $outreachActivities = outreachActivities::whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $outreachActivities = outreachActivities::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
                })
                ->with('usuario')
                ->get();
                return $outreachActivities;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $outreachActivities = outreachActivities::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->whereHas('usuario', function ($query) {
                    $query->where('estado', 1); // Filtrar solo usuarios activos
                })
                ->with('usuario')
                ->get();
        }
    
        // Filtrar resultados en PHP si es necesario
        $outreachActivities = $outreachActivities->filter(function($activity) use ($userName, $userID) {
            $normalizedResearcher = normalizeString($activity->researcherInvolved);
            return $activity->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
        });
    
        return $outreachActivities;
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $outreachActivities = outreachActivities::find($id);
        $input = $request->all();
        $outreachActivities->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importOutreach(Request $request)
    {
        // Arreglo de mapeo para el tipo de actividad
        $activityTypeMapping = [
            '1' => 'Conference',
            '2' => 'Seminar',
            '3' => 'Forum',
            '4' => 'Exhibition',
            '5' => 'Workshop',
            '6' => 'Competition',
            '7' => 'Course',
            '8' => 'Other (specify)',
            '9' => 'Outreach Material',
        ];
    
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Obtén el valor de 'attendantsAmount' del $rowData
            $attendantsAmount = $rowData['Attendant Amount'];
    
            // Verifica si el valor es un entero
            if (!is_numeric($attendantsAmount)) {
                // Si no es un número, establece 'attendantsAmount' en 0
                $attendantsAmount = 0;
            }
    
            // Obtén el valor de 'duration' del $rowData
            $duration = $rowData['Duration [days]'];
    
            // Verifica si el valor es un entero
            if (!is_numeric($duration)) {
                // Si no es un número, establece 'duration' en 0
                $duration = 0;
            }
    
            // Mapear el tipo de actividad
            $activityType = isset($activityTypeMapping[$rowData['Type of Activity']]) ? $activityTypeMapping[$rowData['Type of Activity']] : '';
    
            $outreachActivities = outreachActivities::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'activityType' => $activityType, // Asignar el tipo de actividad mapeado
                'activityName' => $rowData['Event Title'],
                'activityDescription' => $rowData['Activity Description'],
                'date' => $rowData['Date'],
                'attendantsAmount' => $attendantsAmount,
                'duration' => $duration,
                'country' => $rowData['Country'],
                'placeRegion' => $rowData['Place Region'],
                'city' => $rowData['City'],
                // 'undergraduateStudents' => $rowData[''],
                // 'primaryEducationStudents' => $rowData[''],
                // 'secondaryEducationStudents' => $rowData[''],
                // 'generalCommunity' => $rowData[''],
                // 'companiesIndustriesServices' => $rowData[''],
                // 'schoolTeachers' => $rowData[''],
                // 'governmentOfficial' => $rowData[''],
                // 'other' => $rowData[''],
                'nameOfTheMainResponsible' => $rowData['Name of the main responsible'],
                'progressReport' => $rowData['Progress Report'],
                'researcherInvolved' => $rowData['Researchers Involved'],
                'responsibility' => $rowData['Responsibility'],
                'comments' => $rowData['Comentarios'],
            ]);
        }
        
        return response()->json("Publicaciones importadas");
    }
    


     // Función para eliminar un registro
    public function destroy($id)
    {
        outreachActivities::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
