<?php

namespace App\Http\Controllers;
use App\Models\isiPublication;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class isiPublicationsController extends Controller
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
        $isiPublications = IsiPublication::with('usuario')->get();
    
        return response()->json($isiPublications);
    }

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
        
        $isiPublication = isiPublication::create($input);
        return response()->json("Publicación Creada!");
    }

    // Función para detectar registros duplicados
     public function verifyIsi(Request $request)
    {
        $doi = $request['doi'];
        $query = isiPublication::where('doi', $doi)
            ->whereNotNull('doi');
    
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
        
        // Obtener usuario y sus roles
        $user = User::where('id', $userID)->with('roles')->first();
        $isiPublications = collect();
    
        foreach ($user->roles as $rol) {
            if ($rol['name'] == 'Administrator' || $rol['name'] == 'Anid' || $rol['name'] == 'Staff') {
                $roles[] = $rol['name'];
                $administrador = true;
            } elseif ($rol['name'] == 'Titular Researcher') {
                $roles[] = $rol['name'];
                $titularResearcher = true;
            }
        }
    
        // Si es Administrador, puede ver todos los registros
        if ($administrador) {
            $isiPublications = isiPublication::with('usuario')
            ->get();
        } else {
            function normalizeString($string) {
                $string = strtolower($string);
                $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                $string = preg_replace('/[^a-z0-9\s]/', '', $string);
                return trim($string);
            }
    
            // Obtener nombre normalizado del usuario
            $userName = normalizeString($user->name);
            if($userName == 'wael elderedy'){
                $userName = 'wael';
            }
    
            // Si es Titular Researcher, filtra según `idResearchLine`
            if ($titularResearcher) {
                // Obtener `idResearchLine` del usuario actual
                $userResearchLine = $user->idResearchLine;

                // Consultar publicaciones relacionadas con `idResearchLine`
                $isiPublications = isiPublication::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                        ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine);
                })
                ->with('usuario')
                ->get();
                return $isiPublications;
            } else {
                // Filtrado estándar para usuarios sin roles especiales y solo de usuarios activos
                $isiPublications = isiPublication::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                        ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
            }
    
            // Filtrado adicional en PHP
            $isiPublications = $isiPublications->filter(function($event) use ($userName, $userID) {
                $normalizedResearcher = normalizeString($event->researcherInvolved);
                return $event->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
            });
        }
    
        return $isiPublications;
    }
    

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $isiPublication = isiPublication::find($id);

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

        $isiPublication->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo WoS Publications",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json("Publicación Editada");
    }
    
    // Función para importar los registros que vienen desde excel
    public function importIsi(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Dividir el valor del campo researcherInvolved si contiene punto y coma y convertir cada valor según corresponda
            $fundingValues = explode(',', $rowData['Funding']);
            $funding = [];
            
            // Mapear los valores a sus textos correspondientes
            $mapping = [
                '0' => 'Basal Financing Program Funding',
                '1' => 'Other sources'
            ];
            
            // Reemplazar los valores por los textos correspondientes
            foreach ($fundingValues as $value) {
                $trimmedValue = trim($value); // Eliminar espacios alrededor del valor
                if (array_key_exists($trimmedValue, $mapping)) {
                    $funding[] = $mapping[$trimmedValue];
                }
            }
            
            // Unir los textos con coma
            $fundingString = implode(', ', $funding);
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
        
            // Aquí continúa tu lógica para crear o actualizar el modelo con el campo researcherInvolved formateado
            $isiPublicacion = isiPublication::create([
                'status' => $rowData['Status'],
                'idUsuario' => $rowData['idUsuario'],
                'authors' => $rowData['Author(s)'],
                'coauthor' => $rowData['Co-autoria centro'],
                'articleTitle' => $rowData['Article Title'],
                'journalName' => $rowData['Journal Name'],
                'doi' => $rowData['Digital Object Identifier (DOI)'],
                'volume' => $rowData['Volume'],
                'firstPage' => $rowData['First page'],
                'lastPage' => $rowData['Last page'],
                'yearPublished' => $rowData['Year Published'],
                'funding' => $fundingString,
                'mainResearchers' => $rowData['Main Researchers'],
                'associativeResearchers' => $rowData['Associative Researcher'],
                'postDoc' => $rowData['Postdoc.'],
                'researcherInvolved' => $formattedResearcherInvolved,
                'thesisStudents' => $rowData['Thesis Students'],
                'nationalExternalResearchers' => $rowData['National External Researchers'],
                'internationalExternalResearchers' => $rowData['International External Researchers'],
                'comments' => $rowData['Comentario'],
                'progressReport' => $rowData['Progress Report']
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para eliminar un registro
     public function destroy(Request $request, $id)
     {
         $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
         $isiPublication = isiPublication::find($id);
     
         if (!$isiPublication) {
             return response()->json(['message' => 'isiPublication not found'], 404);
         }
     
         // Elimina el libro
         $isiPublication->delete();
     
         // Registra el evento en el log
         SessionLog::create([
             'user_id' => $userId,
             'event_type' => 'delete',
             'description' => "Usuario eliminó un registro con ID {$id} en el módulo WoS Publications",
             'timestamp' => Carbon::now(),
             'ip_address' => $request->ip(),
         ]);
     
         return response()->json(['message' => 'isiPublication successfully deleted']);
     }

    public function useDoi(Request $request)
    {
        $doi = $request->input('doi');
        $wosApiUrl = "https://wos-api.clarivate.com/api/woslite/?databaseId=WOS&usrQuery=DO=" . $doi . "&count=10&firstRecord=1";
        $wosHeaders = [
            "accept" => "application/json",
            "X-ApiKey" => "cc369e7fe729a62bbb01048470df4ed604027c45"
        ];
    
        // Primera llamada: Clarivate WoS API
        $wosResponse = Http::withHeaders($wosHeaders)->get($wosApiUrl);
        $wosData = $wosResponse->json();
        return $wosData;
        // Verificar si se encontró algún registro en WoS
        if (!isset($wosData['QueryResult']['RecordsFound']) || $wosData['QueryResult']['RecordsFound'] == 0) {
            return response()->json([
                'message' => 'No records found with the entered DOI.',
                'status' => 'error'
            ], 404);
        }
    
        // Extraer datos necesarios del primer registro
        $register = $wosData['Data'][0] ?? null;
        $journalName = $register['Source']['SourceTitle'][0] ?? null;
    
        if (!$journalName) {
            return response()->json([
                'message' => 'Journal name not found for the entered DOI.',
                'status' => 'error'
            ], 404);
        }
    
        // Segunda llamada: Scopus API para obtener Q y Impact Factor
        $scopusApiKey = "645c7c515ff007ab09b22957a51c711c";
        $scopusUrl = "https://api.elsevier.com/content/serial/title?title=" . urlencode($journalName) . "&apiKey=" . $scopusApiKey;
    
        $scopusResponse = Http::get($scopusUrl);
        $scopusData = $scopusResponse->json();

        // Procesar la respuesta de Scopus
        $impactFactor = $scopusData['serial-metadata-response']['entry'][0]['citeScoreYearInfoList']['citeScoreCurrentMetric'] ?? 'N/A';
        $quartile = $scopusData['serial-metadata-response']['entry'][0]['subject-area'][0]['@code'] ?? 'N/A';
    
        // Devolver la información combinada
        return response()->json([
            'wosData' => $register,
            'scopusData' => [
                'impactFactor' => $impactFactor,
                'quartile' => $quartile
            ],
            'status' => 'success'
        ]);
    }
}
