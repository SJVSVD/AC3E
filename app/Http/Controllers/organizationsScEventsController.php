<?php

namespace App\Http\Controllers;
use App\Models\organizationsScEvents;
use App\Models\User;
use Illuminate\Http\Request;

class organizationsScEventsController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $file = $request->file('file');
            // Verificar el tamaño del archivo
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }

            $input['file'] = $file->store('organizationsScEvents','public');
        }
        $organizationsScEvents = organizationsScEvents::create($input);
        return response()->json("Organizacion Creada!");
    }

    // Función para detectar registros duplicados
     public function verifyOrganization(Request $request)
    {
        $query = organizationsScEvents::where('eventName', $request['eventName'])
            ->where('startDate', $request['startDate'])
            ->where('idUsuario', $request['idUsuario'])
            ->whereNotNull('eventName')
            ->whereNotNull('startDate');
    
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
        $organizationsScEvents = organizationsScEvents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $userName = User::findOrFail($userID)->name;
            $organizationsScEvents = organizationsScEvents::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $organizationsScEvents = organizationsScEvents::with('usuario')->get();
        }
        return $organizationsScEvents;
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $organization = organizationsScEvents::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $file = $request->file('file');
                // Verificar el tamaño del archivo
                if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                    return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
                }
    
                $input['file'] = $file->store('organizationsScEvents','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $organization = organizationsScEvents::find($request['id'])->update($input);
        return response()->json($organization);
    }

    // Función para importar los registros que vienen desde excel
    public function importOrganization(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Mapeo de número a string para el campo 'typeEvent'
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

            $cityCountry = explode(', ', $rowData['City/country']);

            // Verificar si el formato es distinto al esperado
            if (count($cityCountry) === 2) {
                // Asignar la ciudad y el país a las variables correspondientes
                $city = $cityCountry[0];
                $country = $cityCountry[1];
            } else {
                // Asignar "Global" a ambas variables si el formato es diferente
                $city = 'Global';
                $country = 'Global';
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
            $numberOfParticipants = $rowData['Number of participants'];
            if($numberOfParticipants == '-'){
                $numberOfParticipants = 0;
            }else

            $organizationsScEvents = organizationsScEvents::create([
                'status' => $rowData['Status'],
                'idUsuario' => $rowData['idUsuario'],
                'researcherInvolved' => $researcherInvolvedFormatted,
                'typeEvent' => $typeEvent,
                'eventName' => $rowData['Name course/congress'],
                'country' => $country,
                'city' => $city, // Si la ciudad y el país vienen en un solo campo, podrías asignar el mismo valor a ambos
                'startDate' => $rowData['Start Date'],
                'endingDate' => $rowData['End Date'],
                'numberParticipants' => $numberOfParticipants,
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report']
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $organizationsScEvents = organizationsScEvents::find($id);
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('organizationsScEvents','public');
        }
        $organizationsScEvents->update($input);
        return response()->json($input);
    }

    public function organizationDownload($id){
        $organization = organizationsScEvents::find($id);
        $pathtoFile = public_path().'/defaults/'.$organization['file'];
        return response()->download($pathtoFile);
    }

     // Función para eliminar un registro
    public function destroy($id)
    {
        organizationsScEvents::find($id)->delete();
        return response()->json("Organizacion Eliminado");
    }
}
