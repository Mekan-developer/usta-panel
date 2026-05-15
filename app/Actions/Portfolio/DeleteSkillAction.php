<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Models\Skill;

class DeleteSkillAction
{
    public function __construct(private readonly SkillRepositoryInterface $skills) {}

    public function execute(Skill $skill): void
    {
        $this->skills->delete($skill);
    }
}
