<?php

namespace App\Actions\Server;

use App\Contracts\Repositories\ServerRepositoryInterface;
use App\Models\Server;

class DeleteServerAction
{
    public function __construct(private readonly ServerRepositoryInterface $servers) {}

    public function execute(Server $server): void
    {
        $this->servers->delete($server);
    }
}
