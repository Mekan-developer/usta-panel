<?php

namespace App\Actions\Server;

use App\Contracts\Repositories\ServerRepositoryInterface;
use App\Models\Server;

class StoreServerAction
{
    public function __construct(private readonly ServerRepositoryInterface $servers) {}

    public function execute(array $data): Server
    {
        return $this->servers->create($data);
    }
}
