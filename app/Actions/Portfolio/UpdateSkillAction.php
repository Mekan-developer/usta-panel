<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Models\Skill;

class UpdateSkillAction
{
    public function __construct(private readonly SkillRepositoryInterface $skills) {}

    /** @param array<string, mixed> $data */
    public function execute(Skill $skill, array $data): void
    {
        $this->skills->update($skill, $data);
    }
}
