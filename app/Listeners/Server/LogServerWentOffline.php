<?php

namespace App\Listeners\Server;

use App\Events\Server\ServerWentOffline;
use Illuminate\Support\Facades\Log;

class LogServerWentOffline
{
    public function handle(ServerWentOffline $event): void
    {
        Log::warning("Server went offline: [{$event->server->id}] {$event->server->name} ({$event->server->ip})");
    }
}
