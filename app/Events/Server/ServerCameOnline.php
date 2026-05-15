<?php

namespace App\Events\Server;

use App\Models\Server;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServerCameOnline
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Server $server) {}
}
