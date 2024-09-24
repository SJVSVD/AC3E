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

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $outreachActivities = outreachActivities::where('idUsuario', $userID)->with('usuario')->get();
        
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Administrator'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
            }
        }
        if($administrador == false){
            function normalizeString($string) {
                // Eliminar acentos y convertir a minúsculas
                $string = strtolower($string);
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                // Eliminar caracteres especiales
                $string = preg_replace('/[^a-z0-9\s]/', '', $string);
                // Eliminar espacios adicionales
                $string = trim($string);
                
                return $string;
            }
            // Normaliza el nombre del usuario
            $userName = normalizeString(User::findOrFail($userID)->name);
            if($userName == 'wael elderedy'){
                $userName = 'wael';
            }
            // Obtén las actividades de divulgación relacionadas con el usuario por ID o potencialmente relacionadas por nombre
            $outreachActivities = outreachActivities::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                    ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();

            // Filtra los resultados en PHP si es necesario
            $outreachActivities = $outreachActivities->filter(function($activity) use ($userName, $userID) {
                $normalizedResearcher = normalizeString($activity->researcherInvolved);
                return $activity->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
            });
        }else{
            $outreachActivities = outreachActivities::with('usuario')->get();
        }
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
