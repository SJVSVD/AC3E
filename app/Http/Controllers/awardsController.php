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

    // Función para mostrar registros y verificar roles de administrador o titular researcher
    public function show($userID){
        $roles = [];
        $administrador = false;
        $titularResearcher = false;

        // Obtener usuario con roles
        $user = User::where('id', $userID)->with('roles')->first();

        // Verificar roles
        foreach ($user->roles as $rol) {
            if ($rol['name'] == 'Administrator') {
                $roles[] = $rol['name'];
                $administrador = true;
            } elseif ($rol['name'] == 'Titular Researcher') {
                $roles[] = $rol['name'];
                $titularResearcher = true;
            }
        }

        // Si es Administrador, retornar todos los premios
        if ($administrador) {
            return awards::whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        }

        // Función para normalizar el nombre del usuario
        function normalizeString($string) {
            $string = strtolower($string);
            $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
            return preg_replace('/[^a-z0-9\s]/', '', trim($string));
        }

        // Obtener y normalizar el nombre del usuario
        $userName = normalizeString($user->name);
        if ($userName == 'wael elderedy') {
            $userName = 'wael';
        }

        // Obtener premios para "Titular Researcher"
        if ($titularResearcher) {
            // Obtener `idResearchLine` del usuario actual
            $userResearchLine = $user->idResearchLine;

            // Consultar premios relacionados por `idResearchLine`, `idUsuario` y `researcherInvolved`
            $awards = awards::where(function($query) use ($userResearchLine, $userID, $userName) {
                $query->where('idUsuario', $userID)
                    ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
            })
            ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
            })
            ->with('usuario')
            ->get();
            return $awards;
        } else {
            // Filtrar para usuarios sin roles especiales
            $awards = awards::where(function($query) use ($userName, $userID) {
                $query->where('idUsuario', $userID)
                    ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
            })
            ->with('usuario')
            ->get();
        }

        // Filtrar resultados en PHP si es necesario
        $awards = $awards->filter(function($award) use ($userName, $userID) {
            $normalizedResearcher = normalizeString($award->researcherInvolved);
            return $award->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
        });

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
