<?php

namespace App\Listeners\Server;

use App\Events\Server\ServerCameOnline;
use Illuminate\Support\Facades\Log;

class LogServerCameOnline
{
    public function handle(ServerCameOnline $event): void
    {
        Log::info("Server came online: [{$event->server->id}] {$event->server->name} ({$event->server->ip})");
    }
}
