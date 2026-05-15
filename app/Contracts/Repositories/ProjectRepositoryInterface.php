<?php

namespace App\Contracts\Repositories;

use App\Models\Project;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    /** @return Collection<int, Project> */
    public function allWithImages(): Collection;

    /** @return Collection<int, Project> */
    public function publicActive(): Collection;

    public function create(array $data): Project;

    public function loadImages(Project $project): Project;

    public function update(Project $project, array $data): void;

    public function delete(Project $project): void;
}
