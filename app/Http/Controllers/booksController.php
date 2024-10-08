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
        $books = books::create($input);
        return response()->json("Libro Creada!");
    }

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $books = books::where('centerResearcher', $userID)->with('usuario')->get();
        
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
            // Obtén los libros relacionados con el usuario por ID o potencialmente relacionados por nombre
            $books = books::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                    ->orWhere('centerResearcher', $userID);
            })->with('usuario')->get();

            // Filtra los resultados en PHP si es necesario
            $books = $books->filter(function($book) use ($userName, $userID) {
                $normalizedResearcher = normalizeString($book->researcherInvolved);
                return $book->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
            });
        }else{
            $books = books::with('usuario')->get();
        }
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
