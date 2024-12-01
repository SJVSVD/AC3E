<?php

namespace App\Http\Controllers;
use App\Models\isiPublication;
use App\Models\nonIsiPublication;
use App\Models\books;
use App\Models\awards;
use App\Models\organizationsScEvents;
use App\Models\participationScEvents;
use App\Models\scCollaborations;
use App\Models\thesisStudent;
use App\Models\postDoc;
use App\Models\outreachActivities;
use App\Models\patents;
use App\Models\publicPrivate;
use App\Models\technologyKnowledge;
use App\Models\fundingSources;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class dashboardController extends Controller
{

    public function updateResearchLinesInvolved()
    {
        $modelos = [
            isiPublication::class,
            nonIsiPublication::class,
            books::class,
            awards::class,
            organizationsScEvents::class,
            participationScEvents::class,
            scCollaborations::class,
            thesisStudent::class,
            postDoc::class,
            outreachActivities::class,
            patents::class,
            publicPrivate::class,
            technologyKnowledge::class,
            fundingSources::class,
        ];

        // Recorre todos los modelos para actualizar el campo 'researchLinesInvolved'
        foreach ($modelos as $modelo) {
            $registros = $modelo::whereNotNull('researcherInvolved')->get();

            foreach ($registros as $registro) {
                $researcherNames = explode(',', $registro->researcherInvolved);

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

                // Actualizar el campo 'researchLinesInvolved'
                $registro->researchLinesInvolved = implode(', ', $researchLines);
                $registro->save();
            }
        }

        return response()->json(['message' => 'Research lines updated successfully.']);
    }

    // Se sacan de la base de datos los ultimos 10 registros que se hayan cargado en la pagina
    public function getRegistros()
    {
        $modelos = [
            isiPublication::class,
            nonIsiPublication::class,
            books::class,
            awards::class,
            organizationsScEvents::class,
            participationScEvents::class,
            scCollaborations::class,
            thesisStudent::class,
            postDoc::class,
            outreachActivities::class,
            patents::class,
            publicPrivate::class,
            technologyKnowledge::class,
            fundingSources::class,
        ];
    
        // Crear una colección vacía
        $registros = collect();
    
        foreach ($modelos as $modelo) {
            // Obtener los últimos 100 registros de cada modelo
            $registrosModelo = $modelo::latest()->with('usuario')->limit(100)->get();
    
            // Agregar el nombre del modelo como un atributo 'modulo'
            $registrosModelo->each(function ($registro) use ($modelo) {
                $registro->modulo = class_basename($modelo);
            });
    
            // Concatenar los registros a la colección principal
            $registros = $registros->concat($registrosModelo);
        }
    
        // Ordenar todos los registros por la fecha de creación de manera descendente
        $registros = $registros->sortByDesc(function($registro) {
            return $registro->created_at;
        })->take(100);
    
        return $registros;
    }
    
    
    
    
    public function getRegistrosUser($userId, $cantidad) {

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

        $modelos = [
            isiPublication::class,
            nonIsiPublication::class,
            books::class,
            awards::class,
            organizationsScEvents::class,
            participationScEvents::class,
            scCollaborations::class,
            thesisStudent::class,
            postDoc::class,
            outreachActivities::class,
            patents::class,
            publicPrivate::class,
            technologyKnowledge::class,
            fundingSources::class,
        ];
    
        // Normalizar el nombre del usuario
        $userName = normalizeString(User::findOrFail($userId)->name);
        if($userName == 'wael elderedy'){
            $userName = 'wael';
        }
    
        $registros = new Collection();
    
        foreach ($modelos as $modelo) {
            // Crear una instancia del modelo para usar métodos no estáticos
            $modeloInstance = new $modelo;
    
            // Verificar si el modelo es 'books' y ajustar la consulta en consecuencia
            if ($modelo === books::class) {
                // Buscar por centerResearcher y agregar registros
                $registrosModelo = $modelo::where('centerResearcher', $userId)
                    ->with('usuario')->latest()->limit(100)->get();
            } else {
                // Buscar por idUsuario
                $registrosModelo = $modelo::where('idUsuario', $userId)
                    ->with('usuario')->latest()->limit(100)->get();
            }
    
            // Si el modelo tiene el campo researcherInvolved, buscar también por nombre normalizado
            if (Schema::hasColumn($modeloInstance->getTable(), 'researcherInvolved')) {
                $additionalRegistros = $modelo::where('researcherInvolved', 'LIKE', '%' . $userName . '%')
                    ->with('usuario')->latest()->limit(100)->get();
                
                // Concatenar los resultados encontrados por researcherInvolved
                $registrosModelo = $registrosModelo->concat($additionalRegistros);
            }
    
            // Agregar el nombre del modelo como un atributo 'modulo'
            $registrosModelo->each(function ($registro) use ($modelo) {
                $registro->modulo = class_basename($modelo);
            });
    
            // Concatenar los registros a la colección principal
            $registros = $registros->concat($registrosModelo);
        }
    
        // Ordenar todos los registros por la fecha de creación de manera descendente
        $registros = $registros->sortByDesc('created_at')->take($cantidad);
    
        return $registros;
    }
    
    
}
