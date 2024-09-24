<?php

namespace App\Http\Controllers;
use App\Models\awards;
use App\Models\User;
use Illuminate\Http\Request;

class awardsController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        $awards = awards::create($input);
        return response()->json("Premio Creada!");
    }

    // Función para detectar registros duplicados
     public function verifyAward(Request $request)
    {
        $query = awards::where('awardName', $request['awardName'])
            ->where('institution', $request['institution'])
            ->whereNotNull('awardName')
            ->whereNotNull('institution');
    
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
        $awards = awards::where('idUsuario', $userID)->with('usuario')->get();
        
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
            // Obtén los premios relacionados con el usuario por ID o potencialmente relacionados por nombre
            $awards = Awards::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                    ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();

            // Filtra los resultados en PHP si es necesario
            $awards = $awards->filter(function($award) use ($userName, $userID) {
                $normalizedResearcher = normalizeString($award->researcherInvolved);
                return $award->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
            });
        }else{
            $awards = awards::with('usuario')->get();
        }
        return $awards;
    }

    // Función para importar los registros que vienen desde excel
    public function importAward(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Obtener la cadena del campo 'Researcher Involved'
            $researcherInvolvedString = $rowData['Researchers Involved'];

            // Dividir la cadena en partes usando el delimitador ';'
            $researchersArray = explode(';', $researcherInvolvedString);

            // Iterar sobre cada parte para procesarla y formatearla correctamente
            $researchersFormatted = [];
            foreach ($researchersArray as $researcher) {
                // Eliminar espacios en blanco al principio y al final de cada nombre
                $researcher = trim($researcher);
                // Agregar el nombre al array formateado
                $researchersFormatted[] = $researcher;
            }

            // Unir los nombres formateados en una cadena usando ', ' como separador
            $researcherInvolvedFormatted = implode(', ', $researchersFormatted);
            $award = Awards::create([
                'status' => $rowData['Status'],
                'idUsuario' => $rowData['idUsuario'],
                'researcherInvolved' => $researcherInvolvedFormatted,
                'awardeeName' => $rowData['Awardee(s) Name(s)'],
                'awardName' => $rowData['Award Name'],
                'year' => $rowData['Year'],
                'contributionAwardee' => $rowData['Contribution of the Awardee'],
                'institution' => $rowData['Awarding Institution'],
                'country' => $rowData['Country'],
                'progressReport' => $rowData['Progress Report'],
                'comments' => $rowData['Comentarios']
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $awards = awards::find($id);

        $input = $request->all();
        $awards->update($input);
        return response()->json("Premio Editado");
    }

     // Función para eliminar un registro
    public function destroy($id)
    {
        awards::find($id)->delete();
        return response()->json("Premio Eliminado");
    }
}
