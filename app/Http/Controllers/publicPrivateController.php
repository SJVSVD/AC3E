<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\publicPrivate;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class publicPrivateController extends Controller
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
        $publicPrivate = publicPrivate::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyPublicPrivate(Request $request)
    {
        $query = publicPrivate::where('nameOfActivity', $request['nameOfActivity'])
            ->whereNotNull('nameOfActivity')
            ->where('startDate', $request['startDate'])
            ->whereNotNull('startDate')
            ->where('countryOrigin', $request['countryOrigin'])
            ->whereNotNull('countryOrigin');
    
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
        $publicPrivate = publicPrivate::where('idUsuario', $userID)->with('usuario')->get();
    
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
            $publicPrivate = publicPrivate::whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $publicPrivate = publicPrivate::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
                })
                ->with('usuario')
                ->get();
            return $publicPrivate;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $publicPrivate = publicPrivate::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->whereHas('usuario', function ($query) {
                    $query->where('estado', 1); // Filtrar solo usuarios activos
                })
                ->with('usuario')
                ->get();
        }
    
        return $publicPrivate;
    }
    

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $publicPrivate = publicPrivate::find($id);
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
        $publicPrivate->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importPublicPrivate(Request $request)
    {
        // Arreglo de mapeo para el tipo de agente
        $agentTypeMapping = [
            '1' => 'Private Industry (product or service)',
            '2' => 'Public Services',
            '3' => 'Educational Sector',
            '4' => 'Social - ONG',
            '5' => 'Entrepreneurship',
            '6' => 'Private Education',
            '7' => 'Education (Schools, Education Ministry)',
        ];

        // Arreglo de mapeo para el tipo de conexión
        $typeOfConnectionMapping = [
            '1' => 'Developmental Studies',
            '2' => 'Project Implementation',
            '3' => 'Training',
            '4' => 'Prospective Activity',
            '5' => 'Scientific Training',
            '6' => 'Installation of Scientist',
            '7' => 'Participation in Consulting Commitees',
            '8' => 'MOU, NDA or other',
            '9' => 'Other',
            '10' => 'Temporary Installation (Technician, Professional, Scientific)',
            '11' => 'International agreements of exchange with foreign institutions',
            '12' => 'Participations in instances of public policy definition (consulting councils, advisory committees)',
            '13' => 'International exchange networks',
        ];

        // Función para verificar y convertir la fecha
        function verifyAndConvertDate($date) {
            // Verificar si la fecha está en formato "mes/día/año"
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{2}$/', $date)) {
                // Convertir el formato "mes/día/año" a "año-mes-día"
                $date = date_create_from_format('m/d/y', $date)->format('Y-m-d');
            }
            
            // Intentar crear un objeto DateTime desde la fecha proporcionada
            $dateTime = date_create($date);
            
            // Verificar si el objeto DateTime se creó correctamente y si la fecha es válida
            if ($dateTime && $dateTime->format('Y-m-d') === $date) {
                // Si la fecha es válida, devolverla en formato "año-mes-día"
                return $date;
            } else {
                // Si la fecha no es válida, imprimir un mensaje de error
                echo "Error en la fecha: $date\n";
            }

            // Si la fecha no es válida, devolver null
            return null;
        }

        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Verificar si el campo 'idUsuario' está vacío
            if (empty($rowData['idUsuario'])) {
                continue; // Saltar esta iteración y pasar a la siguiente fila de datos
            }
    
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
    

            // Mapear el tipo de agente
            $agentType = isset($agentTypeMapping[$rowData['Agent Type']]) ? $agentTypeMapping[$rowData['Agent Type']] : '';
    
            $startDate = verifyAndConvertDate($rowData['Start Date']);
            $endDate = verifyAndConvertDate($rowData['End Date']);

            // Mapear el tipo de conexión
            $typeOfConnection = isset($typeOfConnectionMapping[$rowData['Type of Connection']]) ? $typeOfConnectionMapping[$rowData['Type of Connection']] : '';
            $internationalNational = $rowData['Internacional/Nacional'] == 1 ? 'International congress' : 'National congress';
            $publicPrivate = publicPrivate::create([
                'idUsuario'=> $rowData['idUsuario'],
                'status'=> 'Finished',
                'nameOfActivity'=> $rowData['Name of Activity'],
                'agentType'=> $agentType, // Asignar el tipo de agente mapeado
                'typeOfConnection'=> $typeOfConnection, // Asignar el tipo de conexión mapeado
                'placeWhereWasExecuted'=> $rowData['Place(s) where activity was executed'],
                'internationalNational'=> $internationalNational,
                'participationPublicPolicies'=> $rowData['Participation in definition of public policies'],
                'researcherInvolved'=> $formattedResearcherInvolved,
                'startDate'=> $startDate, // Asignar startDate si es una fecha válida
                'endingDate'=> $endDate, // Asignar endDate si es una fecha válida
                'resultsGoals'=> $rowData['Results / Goals'],
                'nameOfOrganization'=> $rowData['Name of Organization'],
                'countryOrigin'=> $rowData['Country/City of origin'],
                'comments'=> $rowData['Comentarios'],
                'progressReport'=> $rowData['Progress Report'],
            ]);
        }
        
        return response()->json("Publicaciones importadas");
    }
    

     // Función para eliminar un registro
    public function destroy($id)
    {
        publicPrivate::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
