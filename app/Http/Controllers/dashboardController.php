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
    public function getRegistros($cantidadRegistros)
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

        $registros = new Collection();

        foreach ($modelos as $modelo) {
            
            $registrosModelo = $modelo::latest()->with('usuario')->take($cantidadRegistros)->get();
            // Agregar el nombre del modelo como un atributo 'modulo'
            $registrosModelo->each(function ($registro) use ($modelo) {
                $registro->modulo = class_basename($modelo);
            });

            $registros = $registros->concat($registrosModelo);
        }

        // Ordenar los registros por fecha de creación
        $registros = $registros->sortByDesc('created_at');

        return $registros;
    }
}
