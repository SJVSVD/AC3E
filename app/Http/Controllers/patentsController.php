<?php

namespace App\Http\Controllers;

use App\Models\extraTables;
use App\Models\patents;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class patentsController extends Controller
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

        $patents = patents::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyPatent(Request $request)
    {
        $query = patents::where('registrationNumber', $request['registrationNumber'])
            ->whereNotNull('registrationNumber');
    
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
        $patents = patents::where('idUsuario', $userID)->with('usuario')->get();
    
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
            $patents = patents::with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $patents = patents::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })

                ->with('usuario')
                ->get();
            return $patents;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $patents = patents::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    

        return $patents;
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $patents = patents::find($id);
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
        $patents->update($input);

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Patents",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importPatent(Request $request)
    {
        // Arreglo de mapeo para el estado de solicitud de aplicación
        $applicationStatusMapping = [
            '1' => 'Approved',
            '2' => 'In progress',
        ];
    
        // Arreglo de mapeo para el tipo de IP
        $ipTypeMapping = [
            '1' => 'Goods',
            '2' => 'Services',
            '3' => 'Commercial establishments',
            '4' => 'Industrial establishments',
            '5' => 'Slogans',
            '6' => 'Collective trademarks',
            '7' => 'Certification trademarks',
            '8' => 'Invention patent',
            '9' => 'Utility model',
            '10' => 'Design (Industrial design and industrial drawing)',
            '11' => 'Layout designs (Topographies) of integrated circuits',
            '12' => 'Geographical indication (GI)',
            '13' => 'Appelation of origin',
            '14' => 'Collective Trademarks',
            '15' => 'Certification Trademarks',
            '16' => 'Copyright',
            '17' => 'Plant varities',
            '18' => 'Traditional knowledge and genetic resources',
            '19' => 'Others',
        ];
    

        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Obtén el valor de 'grantDate' del $rowData
            $grantDate = $rowData['Grant Date'];

            // Mapear el estado de solicitud de aplicación
            $applicationStatus = isset($applicationStatusMapping[$rowData['Application Status']]) ? $applicationStatusMapping[$rowData['Application Status']] : '';
    
            // Mapear el tipo de IP
            $ipType = isset($ipTypeMapping[$rowData['IP Type']]) ? $ipTypeMapping[$rowData['IP Type']] : '';

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
            $patents = patents::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'ipType' => $ipType, // Asignar el tipo de IP mapeado
                'authors' => $rowData['Authors'],
                'institutionOwner' => $rowData['Institution Owner(s)'],
                'countryOfRegistration' => $rowData['Country of Registration'],
                'applicationDate' => $rowData['Application Date'],
                'grantDate' => $grantDate,
                'applicationStatus' => $applicationStatus, // Asignar el estado de solicitud de aplicación mapeado
                'registrationNumber' => $rowData['Registration Number'],
                'nameOfPatent' => $rowData['Name of Patent Applications'],
                'researcherInvolved' => $formattedResearcherInvolved,
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
            ]);
        }
        
        return response()->json("Publicaciones importadas");
    }
    


     // Función para eliminar un registro
     public function destroy(Request $request, $id)
     {
         $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
         $patents = patents::find($id);
     
         if (!$patents) {
             return response()->json(['message' => 'patents not found'], 404);
         }
     
         // Elimina el libro
         $patents->delete();
     
         // Registra el evento en el log
         SessionLog::create([
             'user_id' => $userId,
             'event_type' => 'delete',
             'description' => "Usuario eliminó un registro con ID {$id} en el módulo Patents",
             'timestamp' => Carbon::now(),
             'ip_address' => $request->ip(),
         ]);
     
         return response()->json(['message' => 'patents successfully deleted']);
     }
}
