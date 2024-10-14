<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

class RecycleBinController extends Controller
{
    // Función para listar todos los registros eliminados de todos los modelos
    public function index()
    {
        // Traer registros eliminados de todos los modelos y agregar el nombre del módulo
        $models = [
            ['data' => isiPublication::with('usuario')->onlyTrashed()->get(), 'module' => 'isiPublication'],
            ['data' => nonIsiPublication::with('usuario')->onlyTrashed()->get(), 'module' => 'nonIsiPublication'],
            ['data' => books::with('usuario')->onlyTrashed()->get(), 'module' => 'books'],
            ['data' => awards::with('usuario')->onlyTrashed()->get(), 'module' => 'awards'],
            ['data' => organizationsScEvents::with('usuario')->onlyTrashed()->get(), 'module' => 'organizationsScEvents'],
            ['data' => participationScEvents::with('usuario')->onlyTrashed()->get(), 'module' => 'participationScEvents'],
            ['data' => scCollaborations::with('usuario')->onlyTrashed()->get(), 'module' => 'scCollaborations'],
            ['data' => thesisStudent::with('usuario')->onlyTrashed()->get(), 'module' => 'thesisStudent'],
            ['data' => postDoc::with('usuario')->onlyTrashed()->get(), 'module' => 'postDoc'],
            ['data' => outreachActivities::with('usuario')->onlyTrashed()->get(), 'module' => 'outreachActivities'],
            ['data' => patents::with('usuario')->onlyTrashed()->get(), 'module' => 'patents'],
            ['data' => publicPrivate::with('usuario')->onlyTrashed()->get(), 'module' => 'publicPrivate'],
            ['data' => technologyKnowledge::with('usuario')->onlyTrashed()->get(), 'module' => 'technologyKnowledge'],
            ['data' => fundingSources::with('usuario')->onlyTrashed()->get(), 'module' => 'fundingSources']
        ];

        // Preparar la colección final incluyendo el módulo
        $registros = collect($models)->flatMap(function ($model) {
            return $model['data']->map(function ($item) use ($model) {
                return array_merge($item->toArray(), ['module' => $model['module']]);
            });
        });

        return response()->json($registros);
    }
    private function obtenerModelo($modulo)
    {
        switch ($modulo) {
            case 'WoS Publications':
                return new isiPublication();
            case 'Non WoS Publications':
                return new nonIsiPublication();
            case 'Books':
                return new books();
            case 'Awards':
                return new awards();
            case 'Organization Sc Events':
                return new organizationsScEvents();
            case 'Participation Sc Events':
                return new participationScEvents();
            case 'Visits And Stays':
                return new scCollaborations();
            case 'Thesis Students':
                return new thesisStudent();
            case 'Postdoctoral Fellows':
                return new postDoc();
            case 'Outreach Activities':
                return new outreachActivities();
            case 'Patents':
                return new patents();
            case 'Public-Private Connections':
                return new publicPrivate();
            case 'Tec. and Know Transfer':
                return new technologyKnowledge();
            case 'Funding Sources	':
                return new fundingSources();
            default:
                throw new \Exception('Módulo no encontrado');
        }
    }

    public function restore(Request $request)
    {
        $registros = $request->input('registros');

        foreach ($registros as $registro) {
            $id = $registro['id'];
            $modulo = $registro['modulo'];

            // Obtener el modelo correcto según el módulo
            $modelo = $this->obtenerModelo($modulo);

            // Restaurar el registro
            $modelo->withTrashed()->where('id', $id)->restore();
        }

        return response()->json(['message' => 'Registros restaurados con éxito']);
    }

    public function forceDelete(Request $request)
    {
        $registros = $request->input('registros');

        foreach ($registros as $registro) {
            $id = $registro['id'];
            $modulo = $registro['modulo'];

            // Obtener el modelo correcto según el módulo
            $modelo = $this->obtenerModelo($modulo);

            // Eliminar el registro permanentemente
            $modelo->withTrashed()->where('id', $id)->forceDelete();
        }

        return response()->json(['message' => 'Registros eliminados permanentemente']);
    }

}
