<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\publicPrivate;
use Illuminate\Http\Request;

class publicPrivateController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
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

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $publicPrivate = publicPrivate::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $publicPrivate = publicPrivate::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $publicPrivate = publicPrivate::with('usuario')->get();
        }
        return $publicPrivate;
    }


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $publicPrivate = publicPrivate::find($id);
        $input = $request->all();
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

        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Verificar si el campo 'idUsuario' está vacío
            if (empty($rowData['idUsuario'])) {
                continue; // Saltar esta iteración y pasar a la siguiente fila de datos
            }
    
            $researchers = explode(';', $rowData['Researcher Involved']);
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
    
            // Validar startDate y endDate como fechas válidas
            $startDate = null;

            // Verificar si la fecha es válida
            if (strtotime($rowData['Start Date']) !== false) {
                // Convertir la fecha al formato 'YYYY-MM-DD'
                $startDate = date('Y-m-d', strtotime($rowData['Start Date']));
            }
            
            $endDate = null;

            // Verificar si la fecha es válida
            if (strtotime($rowData['End Date']) !== false) {
                // Convertir la fecha al formato 'YYYY-MM-DD'
                $endDate = date('Y-m-d', strtotime($rowData['End Date']));
            }
    
            // Mapear el tipo de agente
            $agentType = isset($agentTypeMapping[$rowData['Agent Type']]) ? $agentTypeMapping[$rowData['Agent Type']] : '';
    
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
                'endDate'=> $endDate, // Asignar endDate si es una fecha válida
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
