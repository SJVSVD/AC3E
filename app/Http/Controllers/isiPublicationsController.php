<?php

namespace App\Http\Controllers;
use App\Models\isiPublication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class isiPublicationsController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
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

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $isiPublications = isiPublication::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $isiPublications = IsiPublication::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $isiPublications = isiPublication::with('usuario')->get();
        }
        return $isiPublications;
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $isiPublication = isiPublication::find($id);

        $input = $request->all();
        $isiPublication->update($input);
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
    public function destroy($id)
    {
        isiPublication::find($id)->delete();
        return response()->json("Publicación Eliminado");
    }

    public function useDoi(Request $request)
    {
        $doi = $request->input('doi');
        $url = "https://wos-api.clarivate.com/api/woslite/?databaseId=WOS&usrQuery=DO=".$doi."&count=10&firstRecord=1";
        $headers = [
            "accept" => "application/json",
            "X-ApiKey" => "cc369e7fe729a62bbb01048470df4ed604027c45"
        ];

        $response = Http::withHeaders($headers)->get($url);

        $data = $response->json();

        return response()->json($data);
    }
}
