<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckAllServersJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        Server::each(function (Server $server) {
            CheckServerJob::dispatch($server);
        });
    }
}
