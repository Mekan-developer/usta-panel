<?php

namespace App\Repositories;

use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Models\Skill;
use Illuminate\Support\Collection;

class SkillRepository implements SkillRepositoryInterface
{
    /**
     * @return Collection<int, Skill>
     */
    public function all(): Collection
    {
        return Skill::orderBy('category')->orderBy('sort_order')->get();
    }

    /**
     * @return array<string, Collection<int, Skill>>
     */
    public function groupedByCategory(): array
    {
        return Skill::orderBy('sort_order')
            ->get()
            ->groupBy(fn (Skill $skill) => $skill->category->value)
            ->all();
    }

    public function create(array $data): Skill
    {
        return Skill::create($data);
    }

    public function update(Skill $skill, array $data): void
    {
        $skill->update($data);
    }

    public function delete(Skill $skill): void
    {
        $skill->delete();
    }
}
