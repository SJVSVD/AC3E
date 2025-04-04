<?php

namespace App\Http\Controllers;

use App\Models\scCollaborations;
use App\Models\SessionLog;
use App\Models\thesisStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class thesisStudentController extends Controller
{
    public function index(Request $request)
    {
        // Obtener la clave secreta desde el archivo .env
        $secretKey = env('API_SECRET_KEY');
    
        // Obtener la clave enviada en la solicitud
        $providedKey = $request->input('key');
    
        // Verificar si la clave proporcionada coincide con la clave secreta
        if ($providedKey !== $secretKey) {
            return response()->json(['error' => 'Clave incorrecta'], 403);
        }
    
        // Si la clave es correcta, devolver todas las publicaciones
        $thesisStudent = thesisStudent::with('usuario')->where('status','Finished')->get();
        
        return response()->json($thesisStudent);
    }

    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();

        // Manejar carga de archivo si se envía un archivo
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Verificar el tamaño del archivo
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }

            // Guardar el archivo con su nombre original
            $filename = $file->getClientOriginalName();
            $input['file'] = $file->storeAs('thesisExtracts', $filename, 'public');

            // Establecer is_link en 0 ya que se está subiendo un archivo
            $input['is_link'] = 0;
        } elseif (!empty($input['file'])) {
            // Si se proporciona un link en lugar de un archivo
            $input['is_link'] = 1;
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

        $thesisStudent = thesisStudent::create($input);
        return response()->json("Thesis Creada!");
    }


    // Función para detectar registros duplicados
     public function verifyThesis(Request $request)
    {
        $query = thesisStudent::where('runOrPassport', $request['runOrPassport'])
            ->where('degreeDenomination', $request['degreeDenomination'])
            ->whereNotNull('runOrPassport')
            ->whereNotNull('degreeDenomination');
    
        // Si el request trae el campo 'id', agregamos una condición para excluir ese ID de la búsqueda
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function show($userID){
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        
        $user = User::where('id', $userID)->with('roles')->first();
        $thesisStudents = thesisStudent::where('idUsuario', $userID)->with('usuario')->get();
    
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
            $thesisStudents = thesisStudent::with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $thesisStudents = thesisStudent::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->with('usuario')
                ->get();
            return $thesisStudents;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $thesisStudents = thesisStudent::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    

    
        return $thesisStudents;
    }
    

    public function addFile(Request $request)
    {
        $input = $request->all();
    

        // Debug 1: Verificar si la petición llega
        Log::info('Petición recibida en addFile', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip()
        ]);

        // Debug 2: Ver contenido crudo del request
        Log::debug('Contenido del request:', [
            'input' => $request->except(['file']),
            'files' => $request->allFiles(),
            'php_input' => file_get_contents('php://input')
        ]);

        // Debug 3: Verificar archivos (método alternativo)
        $hasFile = $request->hasFile('file');
        $fileReceived = isset($_FILES['file']);
        $fileValid = $hasFile ? $request->file('file')->isValid() : false;

        Log::info('Estado del archivo:', [
            'hasFile' => $hasFile,
            'fileReceived' => $fileReceived,
            'fileValid' => $fileValid,
            'php_files' => $_FILES
        ]);

        // Buscar la tesis correspondiente
        $thesis = thesisStudent::where('id', $input['id'])->first();
        
        // Verificar si se envía un archivo o un link
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Verificar el tamaño del archivo
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }
    
            // Guardar el archivo con su nombre original
            $filename = $file->getClientOriginalName();
            $input['file'] = $file->storeAs('thesisExtracts', $filename, 'public');
    
            // Establecer is_link en 0 ya que se está subiendo un archivo
            $input['is_link'] = 0;
        } elseif (!empty($input['file']) && $input['is_link']) {
            // Si se proporciona un link en lugar de un archivo y el campo is_link es true
            $input['is_link'] = 1;
        } else {
            // Si no se proporciona ni archivo ni link
            return response()->json(['error' => 'You must provide either a file or a link.'], 400);
        }
    
        // Actualizar la tesis
        $thesis->update($input);
        
        return response()->json("File or link added successfully!");
    }
    
    

    public function thesisDownload($id){
        $thesis = thesisStudent::find($id);
        $pathtoFile = public_path().'/defaults/'.$thesis['file'];
        return response()->download($pathtoFile);
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $thesis = thesisStudent::find($id);
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

        $thesis->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Thesis Student",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json('Se ha editado');
    }

    // Función para importar los registros que vienen desde excel
    public function importThesis(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            $gender =  '';
            if($rowData['Gender (M/F)'] == 'M'){
                $gender =  'Male';
            }else{
                $gender = 'Female';
            }

            $academicDegreeMapping = [
                "1" => "Undergraduate degree or profesional title",
                "2" => "Master o equivalent",
                "3" => "PhD degree"
            ];
            
            // Verificar si el campo 'Academic Degree' está presente y es un valor válido en el mapeo
            $academicDegree = isset($rowData['Academic Degree']) && isset($academicDegreeMapping[$rowData['Academic Degree']]) ? $academicDegreeMapping[$rowData['Academic Degree']] : '';
            
            $activityName = '';

            $optionsMapping = [
                '1' => 'Equipment',
                '2' => 'Information',
                '3' => 'Infrastructure',
                '4' => 'Other'
            ];
        

            // Verificar si el campo 'Type of Event' está presente
            if (isset($rowData['Resources provided by the Center'])) {
                // Separar el campo 'Type of Event' por comas
                $events = explode(',', $rowData['Resources provided by the Center']);
            
                // Iterar sobre cada evento
                foreach ($events as &$event) {
                    // Eliminar espacios en blanco alrededor del evento
                    $event = trim($event);
            
                    // Reemplazar el 6 seguido de un espacio y paréntesis por 'Other'
                    $event = preg_replace('/\b4\s*\(/', 'Other (', $event);
            
                    // Verificar si el evento es un número válido en el mapeo
                    if (isset($optionsMapping[$event])) {
                        // Si es un número válido, asignar el valor correspondiente del mapeo
                        $event = $optionsMapping[$event];
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

            $optionsMapping2 = [
                '1' => "Private Education",
                '2' => "Business",
                '3' => "Own entrepreneurship",
                '4' => "Government",
                '5' => "Public Education",
                '6' => "Social-ONG",
                '7' => "In the Center",
                '8' => "None of the above"
            ];

            $researchers = explode(';', $rowData['Researchers Involved']);
            $formattedResearchers = array_map(function($name) {
                // Eliminar espacios en blanco al principio y al final
                $name = trim($name);
                // Dividir el nombre en nombre(s) y apellido
                $parts = explode(' ', $name);
                // Obtener el apellido (última parte del nombre)
                $apellido = array_pop($parts);
                // Unir el/los nombre(s)
                $nombres = implode(' ', $parts);
                // Concatenar apellido y nombre(s) con coma y espacio
                return "$nombres $apellido";
            }, $researchers);
            // Unir los nombres formateados en un solo string separado por coma y espacio
            $formattedResearcherInvolved = implode(', ', $formattedResearchers);
            
            // Verificar si el campo 'options' está presente y es un valor válido en el mapeo
            $posteriorArea = isset($rowData['Posterior working area']) && isset($optionsMapping2[$rowData['Posterior working area']]);
            if (isset($rowData['idUsuario']) && $rowData['idUsuario'] !== null) {
                $thesisStudent = thesisStudent::create([
                    'idUsuario' => $rowData['idUsuario'],
                    'status' => $rowData['Status'],
                    'researcherInvolved' => $formattedResearcherInvolved,
                    'identification' => $rowData['Identification'],
                    'studentName' => $rowData['Student Name'],
                    'runOrPassport' => $rowData['RUN or Passport'],
                    'gender' => $gender,
                    'studentMail' => $rowData['Student Mail'],
                    'thesisStatus' => $rowData['Thesis Status'],
                    'thesisTitle' => $rowData['Thesis Title'],
                    'academicDegree' => $academicDegree,
                    'degreeDenomination' => $rowData['Degree Denomination'],
                    'tutorName' => $rowData['Name1'],
                    'tutorInstitution' => $rowData['Institution1'],
                    'cotutorName' => $rowData['Name2'],
                    'cotutorInstitution' => $rowData['Institution2'],
                    'otherName' => $rowData['Name3'],
                    'otherInstitution' => $rowData['Institution3'],
                    'university' => $rowData['University that gives the degree'],
                    'yearStart' => $rowData['Year in which thesis starts'],
                    'yearThesisEnd' => $rowData['Year in which thesis ends'],
                    'resourcesCenter' => $activityName,
                    'posteriorArea' => $posteriorArea,
                    'institutionPosteriorArea' => $rowData['Institution of Posterior working area'],
                    'comments' => $rowData['Comentarios'],
                    'progressReport' => $rowData['Progress Report'],
                ]);
            } else {
                // Si el registro no tiene 'idUsuario' o es nulo, lo saltamos
                continue;
            }
        }
        
        return response()->json("Publicaciónes importadas");
    }

    public function updateRecords(Request $request)
    {
        // Verificamos que se hayan enviado registros
        if ($request->has('records') && is_array($request->records)) {
            foreach ($request->records as $record) {
                // Buscamos el registro en thesisStudents por ID
                $recordsss = scCollaborations::find($record['ID']);
    
                if ($recordsss) {
                    $cityCountry = explode(', ', $record['Country/City of destination']);

                    // Verificar si el formato es distinto al esperado
                    if (count($cityCountry) === 2) {
                        // Asignar la ciudad y el país a las variables correspondientes
                        $city = $cityCountry[1];
                        $country = $cityCountry[0];
                    }
                    // Actualizamos el campo posteriorArea
                    $recordsss->countryDestination = $country;
                    $recordsss->cityDestination = $city;
                    $recordsss->save();
                }
            }
    
            return response()->json(['message' => 'Registros actualizados con éxito'], 200);
        }
    
        return response()->json(['message' => 'No se enviaron registros válidos'], 400);
    }
    

     // Función para eliminar un registro
     public function destroy(Request $request, $id)
     {
         $userId = $request->input('user_id'); // Obtén el ID del usuario enviado
         $thesisStudent = ThesisStudent::find($id);
     
         if (!$thesisStudent) {
             return response()->json(['message' => 'Thesis not found'], 404);
         }
     
         // Elimina la tesis
         $thesisStudent->delete();
     
         // Registra el evento en el log
         SessionLog::create([
             'user_id' => $userId,
             'event_type' => 'delete',
             'description' => "Usuario eliminó un registro con ID {$id} en el módulo Thesis Student",
             'timestamp' => Carbon::now(),
             'ip_address' => $request->ip(),
         ]);
     
         return response()->json(['message' => 'Thesis successfully deleted']);
     }
     
}
