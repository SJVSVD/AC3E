<?php

namespace App\Http\Controllers;
use App\Models\participationScEvents;
use App\Models\User;
use Illuminate\Http\Request;

class participationsScEventsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('participationScEvents','public');
        }
        $participationScEvents = participationScEvents::create($input);
        return response()->json("Participacion Creada!");
    }

    public function verifyParticipation(Request $request)
    {
        $query = participationScEvents::where('eventName', $request['eventName'])
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

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $participationScEvents = participationScEvents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $participationScEvents = participationScEvents::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $participationScEvents = participationScEvents::with('usuario')->get();
        }
        return $participationScEvents;
    }

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
            $researcherInvolvedString = $rowData['Researcher Involved'];

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

    public function addFile(Request $request){
        $input = $request->all();
        
        $participation = participationScEvents::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $input['file'] = $request->file('file')->store('participationScEvents','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $participation = participationScEvents::find($request['id'])->update($input);
        return response()->json($participation);
    }

    public function update(Request $request, $id)
    {
        $participationScEvents = participationScEvents::find($id);
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('participationScEvents','public');
        }
        $participationScEvents->update($input);
        return response()->json($input);
    }

    public function participationDownload($id){
        $organization = participationScEvents::find($id);
        $pathtoFile = public_path().'/defaults/'.$organization['file'];
        return response()->download($pathtoFile);
    }

    public function destroy($id)
    {
        participationScEvents::find($id)->delete();
        return response()->json("Participacion Eliminado");
    }
}
