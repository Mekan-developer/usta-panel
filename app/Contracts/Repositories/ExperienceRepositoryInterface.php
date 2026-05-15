<?php

namespace App\Contracts\Repositories;

use App\Models\Experience;
use Illuminate\Support\Collection;

interface ExperienceRepositoryInterface
{
    /** @return Collection<int, Experience> */
    public function all(): Collection;

    public function create(array $data): Experience;

    public function update(Experience $experience, array $data): void;

    public function delete(Experience $experience): void;
}
