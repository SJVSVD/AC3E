<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\SessionLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogSessionEvents {
    public function handle($event) {
        $eventType = $event instanceof Login ? 'login' : 'logout';

        SessionLog::create([
            'user_id' => Auth::id(),
            'event_type' => $eventType,
            'description' => 'Usuario ' . ($eventType == 'login' ? 'inició sesión' : 'cerró sesión'),
            'timestamp' => Carbon::now(),
            'ip_address' => request()->ip(),
        ]);
    }
}

