<?php

namespace App\Http\Controllers;
use App\Models\nonIsiPublication;
use App\Models\User;
use Illuminate\Http\Request;

class nonIsiPublicationsController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
    
        // Manejar carga de archivo si se envía un archivo
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Verificar el tamaño del archivo (máximo 20 MB)
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }
    
            // Guardar el archivo con su nombre original en la carpeta nonIsiPublication
            $filename = $file->getClientOriginalName();
            $input['file'] = $file->storeAs('nonIsiPublication', $filename, 'public');
    
            // Establecer is_link en 0 ya que se está subiendo un archivo
            $input['is_link'] = 0;
        } elseif (!empty($input['file'])) {
            // Si se proporciona un link en lugar de un archivo
            $input['is_link'] = 1;
        } else {
            // Si no se proporciona ni archivo ni link
            return response()->json(['error' => 'You must provide either a file or a link.'], 400);
        }
    
        // Crear el registro en la base de datos
        $nonIsiPublication = nonIsiPublication::create($input);
    
        return response()->json("Participacion Creada!");
    }


    // Función para detectar registros duplicados
     public function verifyNonIsi(Request $request)
    {
        $query = nonIsiPublication::where('articleTitle', $request['articleTitle'])
            ->where('journalName', $request['journalName'])
            ->whereNotNull('articleTitle')
            ->whereNotNull('journalName');

        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function nonIsiDownload($id){
        $nonIsi = nonIsiPublication::find($id);
        $pathtoFile = public_path().'/defaults/'.$nonIsi['file'];
        return response()->download($pathtoFile);
    }

    public function addFile(Request $request)
    {
        $input = $request->all();
        
        $nonIsi = nonIsiPublication::where('id', $input['id'])->first();
        
        // Verificar si se envía un archivo o un link
        if (gettype($input['file']) == 'object' && $request->hasFile('file')) {
            $file = $request->file('file');
            
            // Verificar el tamaño del archivo
            if ($file->getSize() > 20480 * 1024) { // 20480 KB = 20 MB
                return response()->json(['error' => 'The file was not saved because it exceeds 20 MB.'], 400);
            }
    
            // Almacenar el archivo con su nombre original en la carpeta 'nonIsiPublication' en el almacenamiento público
            $originalFileName = $file->getClientOriginalName();
            $input['file'] = $file->storeAs('nonIsiPublication', $originalFileName, 'public');
    
            // Establecer is_link en 0 ya que se está subiendo un archivo
            $input['is_link'] = 0;
    
        } elseif (!empty($input['file']) && $input['is_link']) {
            // Si se proporciona un link en lugar de un archivo y el campo is_link es true
            $input['is_link'] = 1;
    
            // Asegurarse de que el campo 'file' sea el link proporcionado
            $input['file'] = $input['file'];
    
        } else {
            // Si no se proporciona ni archivo ni link
            return response()->json(['error' => 'You must provide either a file or a link.'], 400);
        }
    
        // Actualizar el registro con el nuevo archivo o link
        $nonIsi->update($input);
    
        return response()->json($nonIsi);
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

    // Si es Administrador, retornar todas las publicaciones
    if ($administrador) {
        return nonIsiPublication::whereHas('usuario', function ($query) {
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

    // Obtener publicaciones para "Titular Researcher"
    if ($titularResearcher) {
        // Obtener `idResearchLine` del usuario actual
        $userResearchLine = $user->idResearchLine;

        // Consultar publicaciones relacionadas por `idResearchLine`, `idUsuario` y `researcherInvolved`
        $nonIsiPublications = nonIsiPublication::where(function($query) use ($userResearchLine, $userID, $userName) {
            $query->where('idUsuario', $userID)
                  ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
        })
        ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
            $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
        })
        ->with('usuario')
        ->get();
        return $nonIsiPublications;
    } else {
        // Filtrar para usuarios sin roles especiales
        $nonIsiPublications = nonIsiPublication::where(function($query) use ($userName, $userID) {
            $query->where('idUsuario', $userID)
                  ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
        })
        ->whereHas('usuario', function ($query) {
            $query->where('estado', 1); // Filtrar solo usuarios activos
        })
        ->with('usuario')
        ->get();
    }

    // Filtrar resultados en PHP si es necesario
    $nonIsiPublications = $nonIsiPublications->filter(function($publication) use ($userName, $userID) {
        $normalizedResearcher = normalizeString($publication->researcherInvolved);
        return $publication->idUsuario == $userID || strpos($normalizedResearcher, $userName) !== false;
    });

    return $nonIsiPublications;
}


    // Función para importar los registros que vienen desde excel
    public function importNonIsi(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
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

            $nonIsiPublicacion = nonIsiPublication::create([
                'status' => $rowData['Status'],
                'idUsuario' => $rowData['idUsuario'],
                'authors' => $rowData['Author(s)'],
                'indexedBy' => $rowData['Indexed By Scopus Scielo, etc'],
                'articleTitle' => $rowData['Article Title'],
                'journalName' => $rowData['Journal Name'],
                'volume' => $rowData['Volume'],
                'firstPage' => $rowData['First page'],
                'lastPage' => $rowData['Last page'],
                'yearPublished' => $rowData['Year Published'],
                'funding' => $fundingString,
                'mainResearchers' => $rowData['Main Researchers'],
                'associativeResearchers' => $rowData['Associative Researchers'],
                'postDoc' => $rowData['Postdoc.'],
                'thesisStudents' => $rowData['Thesis Students'],
                'nationalExternalResearchers' => $rowData['National External Researchers'],
                'internationalExternalResearchers' => $rowData['International External Researchers'],
                'researcherInvolved' => $formattedResearcherInvolved,
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report']
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $nonIsiPublication = nonIsiPublication::find($id);

        $input = $request->all();
        $nonIsiPublication->update($input);
        return response()->json("Publicación Editada");
    }

     // Función para eliminar un registro
    public function destroy($id)
    {
        nonIsiPublication::find($id)->delete();
        return response()->json("Publicación Eliminado");
    }
}
