<?php

namespace App\Contracts\Repositories;

use App\Models\Server;
use Illuminate\Support\Collection;

interface ServerRepositoryInterface
{
    /** @return Collection<int, Server> */
    public function all(): Collection;

    public function create(array $data): Server;

    public function update(Server $server, array $data): void;

    public function delete(Server $server): void;
}
