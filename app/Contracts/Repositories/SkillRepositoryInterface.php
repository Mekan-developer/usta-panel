<?php

namespace App\Contracts\Repositories;

use App\Models\Skill;
use Illuminate\Support\Collection;

interface SkillRepositoryInterface
{
    /** @return Collection<int, Skill> */
    public function all(): Collection;

    /**
     * @return array<string, Collection<int, Skill>>
     */
    public function groupedByCategory(): array;

    public function create(array $data): Skill;

    public function update(Skill $skill, array $data): void;

    public function delete(Skill $skill): void;
}
