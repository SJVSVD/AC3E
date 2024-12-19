<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class announcementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
        ]);
    
        DB::transaction(function () use ($validated) {
            // Soft delete all existing announcements
            Announcement::query()->delete();
    
            // Create the new announcement
            Announcement::create($validated);
        });
    
        return response()->json(['message' => 'Announcement successfully created']);
    }

    public function index()
    {
        $now = now();
        $announcements = Announcement::where('is_active', true)
            ->whereNull('deleted_at') // Ignora los registros eliminados
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->get();

        return response()->json($announcements);
    }

    public function getActiveAnnouncement()
    {
        $now = Carbon::now();
        $announcement = Announcement::where('is_active', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->latest('start_time') // Obtener el más reciente si hay varios
            ->first();

        if ($announcement) {
            return response()->json($announcement);
        }

        return response()->json(null);
    }


    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete(); // Soft delete

        return response()->json(['message' => 'Announcement deleted successfully']);
    }
}
