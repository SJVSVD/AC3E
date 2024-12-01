<?php

namespace App\Http\Controllers;

use App\Models\SessionLog;
use Illuminate\Http\Request;

class SessionLogController extends Controller {
    public function getRecentSessions() {
        $recentSessions = SessionLog::with('user')
            ->orderBy('timestamp', 'desc')
            ->limit(100)
            ->get();

        return response()->json($recentSessions);
    }
}
