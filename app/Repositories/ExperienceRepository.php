<?php

namespace App\Repositories;

use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Models\Experience;
use Illuminate\Support\Collection;

class ExperienceRepository implements ExperienceRepositoryInterface
{
    /**
     * @return Collection<int, Experience>
     */
    public function all(): Collection
    {
        return Experience::orderBy('sort_order')->get();
    }

    public function create(array $data): Experience
    {
        return Experience::create($data);
    }

    public function update(Experience $experience, array $data): void
    {
        $experience->update($data);
    }

    public function delete(Experience $experience): void
    {
        $experience->delete();
    }
}
