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

    public function show($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        
        $user = User::where('id', $userID)->with('roles')->first();
        $fundingSources = fundingSources::where('idUsuario', $userID)->with('usuario')->get();
    
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
            $fundingSources = fundingSources::whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $fundingSources = fundingSources::where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
                })
                ->with('usuario')
                ->get();
            return $fundingSources;
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $fundingSources = fundingSources::where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        return $fundingSources;
    }
    
    public function fundingSourcesActive($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        $today = Carbon::today();
        
        $user = User::where('id', $userID)->with('roles')->first();
    
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
    
        function normalizeString1($string) {
            $string = strtolower($string);
            $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
            $string = preg_replace('/[^a-z0-9\s]/', '', $string);
            return trim($string);
        }
    
        $userName = normalizeString1($user->name);
        if ($userName == 'wael elderedy') {
            $userName = 'wael';
        }
    
        // Lógica para Administrador
        if ($administrador) {
            $fundingSources = fundingSources::where('finishDate', '>', $today)
            ->whereHas('usuario', function ($query) {
                $query->where('estado', 1); // Filtrar solo usuarios activos
            })
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $fundingSources = fundingSources::where('finishDate', '>', $today)
                ->where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                          ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine)->where('estado', 1);
                })
                ->with('usuario')
                ->get();
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $fundingSources = fundingSources::where('finishDate', '>', $today)
                ->where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                          ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        // Filtrar resultados en PHP por `finishDate`, `idUsuario`, y nombre normalizado si es necesario
        $fundingSources = $fundingSources->filter(function($source) use ($today) {
            return Carbon::parse($source->finishDate)->greaterThan($today);
        });
    
        return $fundingSources;
    }

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $fundingSources = fundingSources::find($id);
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
