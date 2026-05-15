<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function allWithImages(): Collection
    {
        return Project::with('images')->orderBy('sort_order')->get();
    }

    /**
     * Публичная выборка для портфолио — только активные.
     */
    public function publicActive(): Collection
    {
        return Project::with('images')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function loadImages(Project $project): Project
    {
        return $project->load('images');
    }

    public function update(Project $project, array $data): void
    {
        $project->update($data);
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
