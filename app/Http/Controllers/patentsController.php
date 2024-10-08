<?php

namespace App\Http\Controllers;
use App\Models\patents;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class patentsController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
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

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $patents = patents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            // Obtén las patentes relacionadas con el usuario por ID o potencialmente relacionadas por nombre
            $patents = patents::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                    ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();

            // Filtra los resultados en PHP si es necesario
            $patents = $patents->filter(function($patent) use ($userName, $userID) {
                $normalizedResearcher = normalizeString($patent->researcherInvolved);
                return $patent->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
            });
        }else{
            $patents = patents::with('usuario')->get();
        }
        return $patents;
    }


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $patents = patents::find($id);
        $input = $request->all();
        $patents->update($input);
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
    public function destroy($id)
    {
        patents::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
