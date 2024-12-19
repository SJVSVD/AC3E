<?php

namespace App\Http\Controllers;
use App\Models\technologyKnowledge;
use App\Models\User;
use Illuminate\Http\Request;

class technologyKnowledgeController extends Controller
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

        $technologyKnowledge = technologyKnowledge::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyTechnology(Request $request)
    {
        $query = technologyKnowledge::where('description', $request['description'])
            ->whereNotNull('description')
            ->where('nameOfInstitutionInvolved', $request['nameOfInstitutionInvolved'])
            ->whereNotNull('nameOfInstitutionInvolved')
            ->where('year', $request['year'])
            ->whereNotNull('year');
    
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
        $technologyKnowledge = technologyKnowledge::where('idUsuario', $userID)->with('usuario')->get();
        
        $user = User::where('id', $userID)->with('roles')->first();
    
        // Identificar roles del usuario
        if ($user->roles->isEmpty()) {
            $roles[] = '';
        } else {
            foreach ($user->roles as $rol) {
                if ($rol['name'] == 'Administrator') {
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
            $technologyKnowledge = technologyKnowledge::whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $technologyKnowledge = technologyKnowledge::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
                })
                ->with('usuario')
                ->get();
            return $technologyKnowledge;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $technologyKnowledge = technologyKnowledge::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })->whereHas('usuario', function ($query) {
                    $query->where('estado', 1); // Filtrar solo usuarios activos
                })
                ->with('usuario')
                ->get();
        }
    
        // Filtrar resultados en PHP si es necesario
        $technologyKnowledge = $technologyKnowledge->filter(function($knowledge) use ($userName, $userID) {
            $normalizedResearcher = normalizeString($knowledge->researcherInvolved);
            return $knowledge->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
        });
    
        return $technologyKnowledge;
    }
    

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $technologyKnowledge = technologyKnowledge::find($id);
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

        $technologyKnowledge->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importTecAndKnow(Request $request)
    {
        $data = $request->input('data');

        
        foreach ($data as $rowData) {
            $categoryOfTransfer = $rowData['Category of Transfer'];

            $technologyTransfer = strpos($categoryOfTransfer, '1') !== 0;
            $knowledgeTransfer = strpos($categoryOfTransfer, '2') !== 0;
            $typeOfTransferMapping = [
                "1" => "Spin-off",
                "2" => "Licenses",
                "3" => "Technology Transfer Agreement",
                "4" => "Services",
                "5" => "Sale of technology transfer product",
                "6" => "Other",
            ];
            
            $typeOfTransferMapping = [
                "1" => "Spin-off",
                "2" => "Licenses",
                "3" => "Technology Transfer Agreement",
                "4" => "Services",
                "5" => "Sale of technology transfer product",
                "6" => "Other",
            ];
            
            // Verifica si el valor está presente en el mapeado
            $typeOfTransfer = isset($typeOfTransferMapping[$rowData['Type of Transfer']]) ? $typeOfTransferMapping[$rowData['Type of Transfer']] : "";
            
            $technologyKnowledge = technologyKnowledge::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'technologyTransfer' => $technologyTransfer,
                'knowledgeTransfer' => $knowledgeTransfer,
                'typeOfTransfer' => $typeOfTransfer,
                'nameOfBeneficiary' => $rowData['Name of Beneficiary'],
                'country' => $rowData['Country'],
                'city' => $rowData['City'],
                'placeRegion' => $rowData['Place Region'],
                'year' => $rowData['Year'],
                'nameOfResearch' => $rowData['Number of Research Line'],
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
                'description' => $rowData['Description'],
                'nameOfInstitutionInvolved' => $rowData['Name of the Institions Involved'],
                'researcherInvolved' => $rowData['Researchers Involved'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }


     // Función para eliminar un registro
    public function destroy($id)
    {
        technologyKnowledge::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
