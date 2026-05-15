<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Models\Project;

class DeleteProjectAction
{
    public function __construct(private readonly ProjectRepositoryInterface $projects) {}

    public function execute(Project $project): void
    {
        $this->projects->delete($project);
    }
}
