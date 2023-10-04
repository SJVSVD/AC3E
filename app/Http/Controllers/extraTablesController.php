<?php

namespace App\Http\Controllers;
use App\Models\extraTables;
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

    public function showProgressReport(Request $request)
    {
        $input = $request->all();
        $progressReport = extraTables::where('name','progressReport')->pluck('value');
        return response()->json($progressReport[0]);
    }
}
