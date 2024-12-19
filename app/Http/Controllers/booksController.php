<?php

namespace App\Http\Controllers;
use App\Models\books;
use App\Models\User;
use Illuminate\Http\Request;

class booksController extends Controller
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

        $books = books::create($input);
        return response()->json("Libro Creada!");
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

        // Si es Administrador, retornar todos los libros
        if ($administrador) {
            return books::whereHas('usuario', function ($query) {
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

        // Obtener libros para "Titular Researcher"
        if ($titularResearcher) {
            // Obtener `idResearchLine` del usuario actual
            $userResearchLine = $user->idResearchLine;

            // Consultar libros relacionados por `idResearchLine`, `centerResearcher` y `researcherInvolved`
            $books = books::where(function($query) use ($userResearchLine, $userID, $userName) {
                $query->where('centerResearcher', $userID)
                    ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
            })
            ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
            })
            ->with('usuario')
            ->get();
            return $books;
        } else {
            // Filtrar para usuarios sin roles especiales
            $books = books::where(function($query) use ($userName, $userID) {
                $query->where('centerResearcher', $userID)
                    ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
            })
            ->with('usuario')
            ->get();
        }

        // Filtrar resultados en PHP si es necesario
        $books = $books->filter(function($book) use ($userName, $userID) {
            $normalizedResearcher = normalizeString($book->researcherInvolved);
            return $book->centerResearcher == $userID || strpos($normalizedResearcher, $userName) !== false;
        });

        return $books;
    }


    // Función para detectar registros duplicados
     public function verifyBook(Request $request)
    {
        $query = books::where('bookTitle', $request['bookTitle'])
            ->where('chapterTitle', $request['chapterTitle'])
            ->whereNotNull('bookTitle')
            ->whereNotNull('chapterTitle');

        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    // Función para importar los registros que vienen desde excel
    public function importBook(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Mapeo de número a string para el campo 'workType'
            $workTypeMapping = [
                '1' => 'Book (Whole)',
                '2' => 'Book Chapter',
            ];

            // Verificar si el campo 'Work Type' está presente y es un número válido en el mapeo
            $workType = isset($rowData['Work Type']) && isset($workTypeMapping[$rowData['Work Type']]) ? $workTypeMapping[$rowData['Work Type']] : '';

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
            $book = books::create([
                'status' => $rowData['Status'],
                'centerResearcher' => $rowData['idUsuario'],
                'researcherInvolved' => $researcherInvolvedFormatted,
                'workType' => $workType,
                'bookAuthors' => $rowData['Book Authors'],
                'chapterAuthors' => $rowData['Chapter Authors'],
                'bookTitle' => $rowData['Book Title'],
                'chapterTitle' => $rowData['Chapter title'],
                'firstPage' => $rowData['First page'],
                'lastPage' => $rowData['Last page'],
                'editorialCityCountry' => $rowData['Editorial/city/country'],
                'year' => $rowData['Year'],
                'progressReport' => $rowData['Progress Report'],
                'editors' => $rowData['Editors'],
                'ISBN' => $rowData['ISBN'],
                'comments' => $rowData['Comentarios']
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $books = books::find($id);

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
        $books->update($input);
        return response()->json("Libro Editada");
    }

     // Función para eliminar un registro
    public function destroy($id)
    {
        books::find($id)->delete();
        return response()->json("Libro Eliminado");
    }
}
