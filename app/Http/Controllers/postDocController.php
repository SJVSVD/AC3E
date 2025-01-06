<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\postDoc;
use Illuminate\Http\Request;

class postDocController extends Controller
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
        $postDoc = postDoc::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyPostdoc(Request $request)
    {
        $query = postDoc::where('runOrPassport', $request['runOrPassport'])
            ->whereNotNull('runOrPassport');
    
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
        $postDoc = postDoc::where('idUsuario', $userID)->with('usuario')->get();
    
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
            $postDoc = postDoc::whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $postDoc = postDoc::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
                })
                ->with('usuario')
                ->get();
            return $postDoc;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $postDoc = postDoc::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->whereHas('usuario', function ($query) {
                    $query->where('estado', 1); // Filtrar solo usuarios activos
                })
                ->with('usuario')
                ->get();
        }
        
        return $postDoc;
    }    

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $postDoc = postDoc::find($id);
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
        $postDoc->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importPostdoc(Request $request)
    {
        // Arreglo de mapeo para los recursos proporcionados
        $resourcesMapping = [
            '1' => 'Equipment',
            '2' => 'Information',
            '3' => 'Infrastructure',
            '4' => 'Other',
        ];
        
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Convertir el valor del género a Male o Female
            $gender = $rowData['Gender (M/F)'] === 'M' ? 'Male' : 'Female';
            
            // Convertir el valor de RUN / Passport a minúsculas
            $identification = strtolower($rowData['Identification']);
            
            // Mapear el valor de los recursos proporcionados
            $resourcesProvided = '';
            if (isset($rowData['Resources provided by the Center'])) {
                $resources = explode(',', $rowData['Resources provided by the Center']);
                foreach ($resources as $resource) {
                    $resource = trim($resource);
                    if (isset($resourcesMapping[$resource])) {
                        $resourcesProvided .= $resourcesMapping[$resource] . ', ';
                    }
                }
                $resourcesProvided = rtrim($resourcesProvided, ', '); // Eliminar la última coma y espacio
            }
            
            $postDoc = postDoc::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'nameOfPostdoc' => $rowData['Name of Postdoctoral Fellows'],
                'identification' => $identification,
                'runOrPassport' => $rowData['RUN / Passport'], // Asignar el valor convertido a minúsculas
                'gender' => $gender, // Asignar el valor convertido
                'researchTopic' => $rowData['Research Topic'],
                'supervisorName' => $rowData["Tutor's name"],
                'resourcesProvided' => $resourcesProvided, // Asignar los recursos proporcionados
                'fundingSource' => $rowData['Funding Source'],
                'startYear' => $rowData['Start Year'],
                'endingYear' => $rowData['Ending Year'],
                // 'privateSector' => $rowData[''],
                // 'academy1' => $rowData[''],
                // 'business' => $rowData[''],
                // 'ownEntrepreneurship' => $rowData[''],
                // 'publicSector' => $rowData[''],
                // 'government' => $rowData[''],
                // 'academy2' => $rowData[''],
                // 'socialOng' => $rowData[''],
                // 'inTheCenter' => $rowData[''],
                // 'noneOfTheAbove' => $rowData[''],
                'institutionName' => $rowData['Associated Institution'],
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
                'personalEmail' => $rowData['Personal E-mail'],
    
            ]);
        }
        
        return response()->json("Publicaciones importadas");
    }
    

     // Función para eliminar un registro
    public function destroy($id)
    {
        postDoc::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
