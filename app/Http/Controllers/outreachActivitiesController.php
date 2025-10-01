<?php

namespace App\Http\Controllers;

use App\Models\extraTables;
use App\Models\outreachActivities;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class outreachActivitiesController extends Controller
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
        
        $user = User::where('id', $userID)->with('roles')->first();
        $outreachActivities = outreachActivities::where('idUsuario', $userID)->with('usuario')->get();
    
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
            $outreachActivities = outreachActivities::with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $outreachActivities = outreachActivities::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
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
                ->with('usuario')
                ->get();
        }
    
        return $outreachActivities;
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $outreachActivities = outreachActivities::find($id);
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
        $currentProgressReport = extraTables::where('name', 'progressReport')->value('value');
        $input['progressReport'] = $currentProgressReport;
        $outreachActivities->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Outreach Activities",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

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

            // Normalizar attendantsAmount
            $attendantsAmount = is_numeric($rowData['Attendant Amount']) ? $rowData['Attendant Amount'] : 0;

            // Normalizar duration
            $duration = is_numeric($rowData['Duration [days]']) ? $rowData['Duration [days]'] : 0;

            // Mapear el tipo de actividad
            $activityType = isset($activityTypeMapping[$rowData['Type of Activity']]) 
                ? $activityTypeMapping[$rowData['Type of Activity']] 
                : '';

            $outreachActivities = outreachActivities::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'progressReport' => $rowData['Progress Report'],
                'activityType' => $activityType,
                'activityName' => $rowData['Event Title'],
                'activityDescription' => $rowData['Activity Description'],
                'date' => $rowData['Date'],
                'attendantsAmount' => $attendantsAmount,
                'duration' => $duration,
                'country' => $rowData['Country'],
                'placeRegion' => $rowData['Place Region'],
                'city' => $rowData['City'],

                // Audiencias (checkboxes, asumiendo que vendrán como 1 o 0 en el Excel)
                'undergraduateStudents' => $rowData['Undergraduate Students'] ?? 0,
                'primaryEducationStudents' => $rowData['Primary Education Students'] ?? 0,
                'secondaryEducationStudents' => $rowData['Secondary Education Students'] ?? 0,
                'generalCommunity' => $rowData['General Community'] ?? 0,
                'companiesIndustriesServices' => $rowData['Companies, Industries, Services'] ?? 0,
                'schoolTeachers' => $rowData['School Teachers'] ?? 0,
                'governmentOfficial' => $rowData['Government Official'] ?? 0,
                'other' => $rowData['Other Audience'] ?? 0,

                // Outreach Material (nuevos campos)
                'materialType' => $rowData['Material Type'] ?? null,
                'materialName' => $rowData['Material Name'] ?? null,
                'publicationDate' => $rowData['Publication Date'] ?? null,
                'publicationMean' => $rowData['Publication Mean'] ?? null,
                'publicationOther' => $rowData['Publication Other'] ?? null,
                'downloads' => is_numeric($rowData['Downloads'] ?? null) ? $rowData['Downloads'] : 0,
                'twitterMentions' => is_numeric($rowData['TwitterMentions'] ?? null) ? $rowData['TwitterMentions'] : 0,
                'facebookMentions' => is_numeric($rowData['FacebookMentions'] ?? null) ? $rowData['FacebookMentions'] : 0,
                'otherMentions' => is_numeric($rowData['OtherMentions'] ?? null) ? $rowData['OtherMentions'] : 0,

                // Otros datos
                'nameOfTheMainResponsible' => $rowData['Name of the main responsible'],
                'researcherInvolved' => $rowData['Researcher Involved'],
                'responsibility' => $rowData['Responsibility'],
                'comments' => $rowData['Comentarios'],
            ]);
        }
        
        return response()->json("Publicaciones importadas");
    }

    

     // Función para eliminar un registro
    public function destroy(Request $request, $id)
    {
        $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
        $outreachActivities = outreachActivities::find($id);
    
        if (!$outreachActivities) {
            return response()->json(['message' => 'outreachActivities not found'], 404);
        }
    
        // Elimina el libro
        $outreachActivities->delete();
    
        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $userId,
            'event_type' => 'delete',
            'description' => "Usuario eliminó un registro con ID {$id} en el módulo Outreach Activities",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);
    
        return response()->json(['message' => 'Outreach successfully deleted']);
    }
}
