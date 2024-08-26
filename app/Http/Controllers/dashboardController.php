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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class dashboardController extends Controller
{
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
    
        $registros = new Collection();
    
        foreach ($modelos as $modelo) {
            // Verificar si el modelo es 'books' y ajustar la consulta en consecuencia
            if ($modelo === books::class) {
                $registrosModelo = $modelo::where('centerResearcher', $userId)
                    ->with('usuario')->latest()->limit(100)->get();
            } else {
                $registrosModelo = $modelo::where('idUsuario', $userId)
                    ->with('usuario')->latest()->limit(100)->get();
            }
            
            // Agregar el nombre del modelo como un atributo 'modulo'
            $registrosModelo->each(function ($registro) use ($modelo) {
                $registro->modulo = class_basename($modelo);
            });
    
            // Concatenar los registros a la colección principal
            $registros = $registros->concat($registrosModelo);
        }
    
        // Ordenar todos los registros por la fecha de creación de manera descendente
        $registros = $registros->sortByDesc('created_at')->take(100);
    
        return $registros;
    }
    
    
}
