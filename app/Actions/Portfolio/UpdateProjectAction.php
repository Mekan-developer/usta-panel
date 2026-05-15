<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Models\Project;

class UpdateProjectAction
{
    public function __construct(private readonly ProjectRepositoryInterface $projects) {}

    public function execute(Project $project, array $data): void
    {
        $this->projects->update($project, $data);
    }
}
