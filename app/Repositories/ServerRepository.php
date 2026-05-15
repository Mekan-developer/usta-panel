<?php

namespace App\Repositories;

use App\Contracts\Repositories\ServerRepositoryInterface;
use App\Models\Server;
use Illuminate\Support\Collection;

class ServerRepository implements ServerRepositoryInterface
{
    public function all(): Collection
    {
        return Server::latest()->get();
    }

    public function create(array $data): Server
    {
        return Server::create($data);
    }

    public function update(Server $server, array $data): void
    {
        $server->update($data);
    }

    public function delete(Server $server): void
    {
        $server->delete();
    }
}
