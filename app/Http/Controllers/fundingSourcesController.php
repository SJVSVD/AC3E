<?php

namespace App\Http\Controllers;
use App\Models\fundingSources;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class fundingSourcesController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        $fundingSources = fundingSources::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyFunding(Request $request)
    {
        $query = fundingSources::where('programContest', $request['programContest'])
            ->whereNotNull('programContest')
            ->where('projectTitle', $request['projectTitle'])
            ->whereNotNull('projectTitle');
    
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
        $fundingSources = fundingSources::where('idUsuario', $userID)->with('usuario')->get();
        
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
            // Obtén las fuentes de financiación relacionadas con el usuario por ID o potencialmente relacionadas por nombre
            $fundingSources = fundingSources::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                    ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();

            // Filtra los resultados en PHP si es necesario
            $fundingSources = $fundingSources->filter(function($source) use ($userName,$userID) {
                $normalizedResearcher = normalizeString($source->researcherInvolved);
                return $source->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
            });
        }else{
            $fundingSources = fundingSources::with('usuario')->get();
        }
        return $fundingSources;
    }

        // Función para mostrar registros y verificar si es administrador  o no lo es
        public function fundingSourcesActive($userID){
            // Seleccionar datos relacionados con el usuario:
            $roles = [];
            $administrador = false;
            $today = Carbon::today();
            $fundingSources = fundingSources::where('finishDate', '>', $today)->where('idUsuario', $userID)->with('usuario')->get();
            
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
                // Obtén las fuentes de financiación relacionadas con el usuario por ID o potencialmente relacionadas por nombre
                $fundingSources = fundingSources::where('finishDate', '>', $today)->where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                        ->orWhere('idUsuario', $userID);
                })->with('usuario')->get();
    
                // Filtra los resultados en PHP si es necesario
                $fundingSources = $fundingSources->filter(function($source) use ($userName,$userID) {
                    $normalizedResearcher = normalizeString($source->researcherInvolved);
                    return $source->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
                });
            }else{
                $fundingSources = fundingSources::where('finishDate', '>', $today)->with('usuario')->get();
            }
            return $fundingSources;
        }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $fundingSources = fundingSources::find($id);
        $input = $request->all();
        $fundingSources->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importFunding(Request $request)
    {
        $data = $request->input('data');

        function normalizeTypeSource($typeSource) {
            $typeSource = strtolower(trim($typeSource));
        
            if (strpos($typeSource, 'public') !== false) {
                return 'Public funds';
            } elseif (strpos($typeSource, 'private') !== false || strpos($typeSource, 'productive') !== false) {
                return 'Productive/Private funds';
            } elseif (strpos($typeSource, 'international') !== false) {
                return 'International funds';
            } else {
                return null; // O asigna un valor por defecto si es necesario
            }
        }

        function normalizeTypeCollaboration($typeCollaboration) {
            $typeCollaboration = strtolower(trim($typeCollaboration));
        
            if (strpos($typeCollaboration, 'technical') !== false) {
                return 'Technical assitance';
            } elseif (strpos($typeCollaboration, 'patent') !== false) {
                return 'Patents';
            } elseif (strpos($typeCollaboration, 'license') !== false) {
                return 'License';
            } elseif (strpos($typeCollaboration, 'grand') !== false || strpos($typeCollaboration, 'competitive') !== false) {
                return 'Grand/Competitive fund';
            } else {
                return 'Other (Specify in comments)'; // Valor por defecto si no coincide con las otras opciones
            }
        }
        
        foreach ($data as $rowData) {
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
            $fundingSources = fundingSources::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => 'Finished',
                'typeSources' => normalizeTypeSource($rowData['Type Sources']),
                'researcherInvolved' => $formattedResearcherInvolved,
                'nameOfInstitution' => $rowData['Name of the Institution'],
                'programContest' => $rowData['Program / Contest'],
                'projectTitle' => $rowData['Project title'],
                'principalResearcher' => $rowData['Principal Researcher'],
                'startDate' => $rowData['Start'],
                'finishDate' => $rowData['Finish'],
                'comments' => $rowData['Comentarios'],
                'inCash' => is_numeric($rowData['In Cash']) ? $rowData['In Cash'] : null,
                'typeOfCollaboration' => normalizeTypeCollaboration($rowData['Type of collaboration']),
                'progressReport' => $rowData['Progress Report'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para eliminar un registro
    public function destroy($id)
    {
        fundingSources::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
