<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Models\Project;

class StoreProjectAction
{
    public function __construct(private readonly ProjectRepositoryInterface $projects) {}

    public function execute(array $data): Project
    {
        return $this->projects->create($data);
    }
}
