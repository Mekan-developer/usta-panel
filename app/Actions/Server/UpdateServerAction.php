<?php

namespace App\Actions\Server;

use App\Contracts\Repositories\ServerRepositoryInterface;
use App\Models\Server;

class UpdateServerAction
{
    public function __construct(private readonly ServerRepositoryInterface $servers) {}

    public function execute(Server $server, array $data): void
    {
        if (empty($data['token'])) {
            unset($data['token']);
        }

        $this->servers->update($server, $data);
    }
}
