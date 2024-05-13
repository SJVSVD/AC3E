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
        $technologyKnowledge = technologyKnowledge::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyTechnology(Request $request)
    {
        $query = technologyKnowledge::where('typeOfTransfer', $request['typeOfTransfer'])
            ->whereNotNull('typeOfTransfer')
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

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $technologyKnowledge = technologyKnowledge::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $technologyKnowledge = technologyKnowledge::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $technologyKnowledge = technologyKnowledge::with('usuario')->get();
        }
        return $technologyKnowledge;
    }


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $technologyKnowledge = technologyKnowledge::find($id);
        $input = $request->all();
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
                'researcherInvolved' => $rowData['Researcher Involved'],
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
