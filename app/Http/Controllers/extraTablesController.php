<?php

namespace App\Http\Controllers;

use App\Models\awards;
use App\Models\books;
use App\Models\extraTables;
use App\Models\fundingSources;
use App\Models\isiPublication;
use App\Models\nonIsiPublication;
use App\Models\organizationsScEvents;
use App\Models\outreachActivities;
use App\Models\participationScEvents;
use App\Models\patents;
use App\Models\postDoc;
use App\Models\publicPrivate;
use App\Models\scCollaborations;
use App\Models\thesisStudent;
use Illuminate\Http\Request;

class extraTablesController extends Controller
{
    public function editProgressReport(Request $request)
    {
        $input = $request->all();
        $progressReport = extraTables::where('name','progressReport');
        $progressReport->update($input);
        return response()->json("Progress report updated!");
    }

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function showProgressReport(Request $request)
    {
        $input = $request->all();
        $progressReport = extraTables::where('name','progressReport')->pluck('value');
        return response()->json($progressReport[0]);
    }

    public function deleteRecords(Request $request)
{
    $module = $request->input('module');
    $ids = $request->input('ids');

    if (empty($ids)) {
        return response()->json(['error' => 'No records selected'], 400);
    }

    switch ($module) {
        case 'thesis':
            thesisStudent::whereIn('id', $ids)->delete();
            break;
        case 'wos-publications':
            isiPublication::whereIn('id', $ids)->delete();
            break;
        case 'non-wos-publications':
            nonIsiPublication::whereIn('id', $ids)->delete();
            break;
        case 'awards':
            awards::whereIn('id', $ids)->delete();
            break;
        case 'books':
            books::whereIn('id', $ids)->delete();
            break;
        case 'organization-sc':
            organizationsScEvents::whereIn('id', $ids)->delete();
            break;
        case 'participation-sc':
            participationScEvents::whereIn('id', $ids)->delete();
            break;
        case 'conjoint':
            scCollaborations::whereIn('id', $ids)->delete();
            break;
        case 'funding':
            fundingSources::whereIn('id', $ids)->delete();
            break;
        case 'outreach':
            outreachActivities::whereIn('id', $ids)->delete();
            break;
        case 'patents':
            patents::whereIn('id', $ids)->delete();
            break;
        case 'postdoc':
            postDoc::whereIn('id', $ids)->delete();
            break;
        case 'public-private':
            publicPrivate::whereIn('id', $ids)->delete();
            break;
        default:
            return response()->json(['error' => 'Invalid module'], 400);
    }

    return response()->json(['success' => 'Records deleted successfully']);
}

}
