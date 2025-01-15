<?php

namespace App\Http\Controllers;
use App\Models\participationScEvents;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class participationsScEventsController extends Controller
{


    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
    
        // Manejar carga de archivo si se envía un archivo
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Verificar el tamaño del archivo (máximo 20 MB)
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }
    
            // Guardar el archivo con su nombre original en la carpeta participationScEvents
            $filename = $file->getClientOriginalName();
            $input['file'] = $file->storeAs('participationScEvents', $filename, 'public');
    
            // Establecer is_link en 0 ya que se está subiendo un archivo
            $input['is_link'] = 0;
        } elseif (!empty($input['file'])) {
            // Si se proporciona un link en lugar de un archivo
            $input['is_link'] = 1;
        } else {
            // Si no se proporciona ni archivo ni link
            return response()->json(['error' => 'You must provide either a file or a link.'], 400);
        }
    
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

        // Crear el registro en la base de datos
        $participationScEvents = participationScEvents::create($input);
    
        return response()->json("Participacion Creada!");
    }
    

    // Función para detectar registros duplicados
     public function verifyParticipation(Request $request)
    {
        $query = participationScEvents::where('eventName', $request['eventName'])
            ->where('presentationTitle', $request['presentationTitle'])
            ->where('idUsuario', $request['idUsuario'])
            ->whereNotNull('eventName')
            ->whereNotNull('presentationTitle');
    
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    // Función para mostrar registros y verificar roles de administrador o titular researcher
    public function show($userID) {
        $roles = [];
        $administrador = false;
        $titularResearcher = false;

        // Obtener usuario con roles
        $user = User::where('id', $userID)->with('roles')->first();

        // Verificar roles
        foreach ($user->roles as $rol) {
            if ($rol['name'] == 'Administrator' || $rol['name'] == 'Anid' || $rol['name'] == 'Staff') {
                $roles[] = $rol['name'];
                $administrador = true;
            } elseif ($rol['name'] == 'Titular Researcher') {
                $roles[] = $rol['name'];
                $titularResearcher = true;
            }
        }

        // Si es Administrador, retornar todos los eventos SC
        if ($administrador) {
            return participationScEvents::with('usuario')
            ->get();
        }

        // Función para normalizar el nombre del usuario
        function normalizeString($string) {
            $string = strtolower($string);
            $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
            return preg_replace('/[^a-z0-9\s]/', '', trim($string));
        }

        // Obtener y normalizar el nombre del usuario
        $userName = normalizeString($user->name);
        if ($userName == 'wael elderedy') {
            $userName = 'wael';
        }

        // Obtener eventos SC para "Titular Researcher"
        if ($titularResearcher) {
            // Obtener `idResearchLine` del usuario actual
            $userResearchLine = $user->idResearchLine;

            // Consultar eventos relacionados por `idResearchLine`, `idUsuario` y `researcherInvolved`
            $participationScEvents = participationScEvents::where(function($query) use ($userResearchLine, $userID, $userName) {
                $query->where('idUsuario', $userID)
                    ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
            })
            ->with('usuario')
            ->get();
            return $participationScEvents;
        } else {
            // Filtrar para usuarios sin roles especiales
            $participationScEvents = participationScEvents::where(function($query) use ($userName, $userID) {
                $query->where('idUsuario', $userID)
                    ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
            })
            ->with('usuario')
            ->get();
        }

        // Filtrar resultados en PHP si es necesario
        $participationScEvents = $participationScEvents->filter(function($event) use ($userName, $userID) {
            $normalizedResearcher = normalizeString($event->researcherInvolved);
            return $event->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
        });

        return $participationScEvents;
    }

    // Función para importar los registros que vienen desde excel
    public function importParticipation(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            $typeEventMapping = [
                '1' => 'International congress',
                '2' => 'National Congress',
                '3' => 'Workshop',
                '4' => 'Course',
                '5' => 'Conference',
                '6' => 'Other'
            ];
            
            $typeEvent = '';
            
            // Verificar si el campo 'Type of Event' está presente
            if (isset($rowData['Type of Event'])) {
                // Separar el campo 'Type of Event' por comas
                $events = explode(',', $rowData['Type of Event']);
            
                // Iterar sobre cada evento
                foreach ($events as &$event) {
                    // Eliminar espacios en blanco alrededor del evento
                    $event = trim($event);
            
                    // Reemplazar el 6 seguido de un espacio y paréntesis por 'Other'
                    $event = preg_replace('/\b6\s*\(/', 'Other (', $event);
            
                    // Verificar si el evento es un número válido en el mapeo
                    if (isset($typeEventMapping[$event])) {
                        // Si es un número válido, asignar el valor correspondiente del mapeo
                        $event = $typeEventMapping[$event];
                    } elseif ($event === '6') {
                        // Si el evento es '6', asignar el valor 'Other'
                        $event = 'Other';
                    }
                }
            
                // Unir los eventos nuevamente en una cadena separada por comas
                $typeEvent = implode(', ', $events);
            
                // Agregar un punto al final de la cadena si no está presente
                if (!empty($typeEvent)) {
                    $typeEvent .= '.';
                }
            }  

            // Mapeo de número a string para el campo 'typeEvent'
            $typeParticipationMapping = [
                "1" => "Paper presentation",
                "2" => "Talk",
                "3" => "Session chair",
                "4" => "Keynote",
                "5" => "Just Assistance",
                "6" => "Other",
            ];

            $typeParticipation = '';
            
            // Verificar si el campo 'Type of participation' está presente
            if (isset($rowData['Type of participation'])) {
                // Separar el campo 'Type of participation' por comas
                $events = explode(',', $rowData['Type of participation']);
            
                // Iterar sobre cada evento
                foreach ($events as &$event) {
                    // Eliminar espacios en blanco alrededor del evento
                    $event = trim($event);
            
                    // Reemplazar el 6 seguido de un espacio y paréntesis por 'Other'
                    $event = preg_replace('/\b6\s*\(/', 'Other (', $event);
            
                    // Verificar si el evento es un número válido en el mapeo
                    if (isset($typeParticipationMapping[$event])) {
                        // Si es un número válido, asignar el valor correspondiente del mapeo
                        $event = $typeParticipationMapping[$event];
                    } elseif ($event === '6') {
                        // Si el evento es '6', asignar el valor 'Other'
                        $event = 'Other';
                    }
                }
            
                // Unir los eventos nuevamente en una cadena separada por comas
                $typeParticipation = implode(', ', $events);
            
                // Agregar un punto al final de la cadena si no está presente
                if (!empty($typeParticipation)) {
                    $typeParticipation .= '.';
                }
            }  

            // Obtener la cadena del campo 'Researcher Involved'
            $researcherInvolvedString = $rowData['Researchers Involved'];

            // Dividir la cadena en partes usando el delimitador ';'
            $researchersArray = explode(';', $researcherInvolvedString);

            // Iterar sobre cada parte para procesarla y formatearla correctamente
            $researchersFormatted = [];
            foreach ($researchersArray as $researcher) {
                // Eliminar espacios en blanco al principio y al final de cada nombre
                $researcher = trim($researcher);
                // Agregar el nombre al array formateado
                $researchersFormatted[] = $researcher;
            }


            // Unir los nombres formateados en una cadena usando ', ' como separador
            $researcherInvolvedFormatted = implode(', ', $researchersFormatted);

            $participationScEvents = participationScEvents::create([

                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'researcherInvolved' => $researcherInvolvedFormatted,
                'presentationTitle' => $rowData['Presentation title'],
                'typeOfParticipation' => $typeParticipation,
                'typeEvent' => $typeEvent,
                'eventName' => $rowData['Name course/congress'],
                'country' => $rowData['Country'],
                'city' => $rowData['City'],
                'startDate' => $rowData['Start Date'],
                'endingDate' => $rowData['End Date'],
                'nameOfParticipants' => $rowData['Name of participants'],
                'progressReport' => $rowData['Progress Report'],
                'comments' => $rowData['Comentarios'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }


    public function addFile(Request $request)
    {
        $input = $request->all();
    
        $participationScEvents = participationScEvents::where('id', $input['id'])->first();
    
        // Verificar si se envía un archivo o un link
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Verificar el tamaño del archivo
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }
    
            // Guardar el archivo con su nombre original
            $filename = $file->getClientOriginalName();
            $input['file'] = $file->storeAs('participationsScEvents', $filename, 'public');
    
            // Establecer is_link en 0 ya que se está subiendo un archivo
            $input['is_link'] = 0;
        } elseif (!empty($input['file']) && $input['is_link']) {
            // Si se proporciona un link en lugar de un archivo y el campo is_link es true
            $input['is_link'] = 1;
        } else {
            // Si no se proporciona ni archivo ni link
            return response()->json(['error' => 'You must provide either a file or a link.'], 400);
        }
    
        // Actualizar el registro en la base de datos
        $participationScEvents->update($input);
    
        return response()->json("File or link added successfully!");
    }    


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $participationScEvents = participationScEvents::find($id);
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('participationScEvents','public');
        }
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
        $participationScEvents->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Participation Sc Events",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json($input);
    }

    public function participationDownload($id){
        $organization = participationScEvents::find($id);
        $pathtoFile = public_path().'/defaults/'.$organization['file'];
        return response()->download($pathtoFile);
    }

     // Función para eliminar un registro
     public function destroy(Request $request, $id)
     {
         $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
         $participationScEvents = participationScEvents::find($id);
     
         if (!$participationScEvents) {
             return response()->json(['message' => 'participationScEvents not found'], 404);
         }
     
         // Elimina el libro
         $participationScEvents->delete();
     
         // Registra el evento en el log
         SessionLog::create([
             'user_id' => $userId,
             'event_type' => 'delete',
             'description' => "Usuario eliminó un registro con ID {$id} en el módulo Participation Sc Events",
             'timestamp' => Carbon::now(),
             'ip_address' => $request->ip(),
         ]); 
     
         return response()->json(['message' => 'participationScEvents successfully deleted']);
     }
}
