<?php

namespace App\Http\Controllers;
use App\Models\scCollaborations;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class conjointProjectController extends Controller
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
        $scCollaborations = scCollaborations::create($input);
        return response()->json("Colaboracion Creada!");
    }

    // Función para detectar registros duplicados
     public function verifyConjoint(Request $request)
    {
        $query = scCollaborations::where('moduleType', 1)
            ->where('nameOfExternalResearcher', $request['nameOfExternalResearcher'])
            ->where('beginningDate', $request['beginningDate'])
            ->where('endingDate', $request['endingDate'])
            ->whereNotNull('endingDate')
            ->whereNotNull('beginningDate')
            ->whereNotNull('nameOfExternalResearcher');
    
        // Si el request trae el campo 'id', agregamos una condición para excluir ese ID de la búsqueda
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
            $scCollaborations = scCollaborations::where('moduleType', 1)
            ->with('usuario')
            ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $scCollaborations = scCollaborations::where('moduleType', 1)
                ->where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                        ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine);
                })
                ->with('usuario')
                ->get();
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $scCollaborations = scCollaborations::where('moduleType', 1)
                ->where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                        ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }

        $scCollaborations = $scCollaborations->filter(function($collaboration){
            return $collaboration->moduleType == 1;
        });
    
        return $scCollaborations;
    }
    
    public function conjointProjectsActive($userID) {
        // Inicializar variables y roles
        $roles = [];
        $administrador = false;
        $titularResearcher = false;
        $today = Carbon::today();
        $scCollaborations = scCollaborations::where('idUsuario', $userID)
            ->where('moduleType', 1)
            ->with('usuario')
            ->get();
        
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
            $scCollaborations = scCollaborations::where('endingDate', '>', $today)
                ->where('moduleType', 1)
                ->with('usuario')
                ->get();
        } elseif ($titularResearcher) {
            // Lógica para Titular Researcher basada en `idResearchLine`
            $userResearchLine = $user->idResearchLine;
    
            $scCollaborations = scCollaborations::where('endingDate', '>', $today)
                ->where('moduleType', 1)
                ->where(function($query) use ($userResearchLine, $userID, $userName) {
                    $query->where('idUsuario', $userID)
                        ->orWhere('researcherInvolved', 'LIKE', "%{$userName}%");
                })
                ->orWhereHas('usuario', function ($query) use ($userResearchLine) {
                    $query->where('idResearchLine', $userResearchLine);
                })
                ->with('usuario')
                ->get();
        } else {
            // Lógica estándar para usuarios sin roles especiales
            $scCollaborations = scCollaborations::where('endingDate', '>', $today)
                ->where('moduleType', 1)
                ->where(function($query) use ($userName, $userID) {
                    $query->where('researcherInvolved', 'LIKE', "%{$userName}%")
                        ->orWhere('idUsuario', $userID);
                })
                ->with('usuario')
                ->get();
        }
    
        $scCollaborations = $scCollaborations->filter(function($collaboration) use ($today) {
            return $collaboration->moduleType == 1 && 
                   Carbon::parse($collaboration->endingDate)->greaterThan(Carbon::parse($today)); // Asegurar comparación de fechas y moduleType
        });
    
        return $scCollaborations;
    }
    

     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $scCollaborations = scCollaborations::find($id);
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
        $scCollaborations->update($input);
        
        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $input['idUsuario'],
            'event_type' => 'update',
            'description' => "Usuario edito el registro con ID {$id} en el módulo Conjoint Project",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json($input);
    }


     // Función para eliminar un registro
     public function destroy(Request $request, $id)
    { 
        $userId = $request->input('user_id'); // Obtiene el ID del usuario desde la solicitud
        $scCollaboration = scCollaborations::find($id);
        if (!$scCollaboration) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $scCollaboration->delete();

        // Registra el evento en el log
        SessionLog::create([
            'user_id' => $userId,
            'event_type' => 'delete',
            'description' => "Usuario eliminó un registro con ID {$id} en el módulo Conjoint Project",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);
            

        return response()->json("Colaboracion Eliminado");
    }
}
