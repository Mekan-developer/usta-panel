<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Models\Skill;

class StoreSkillAction
{
    public function __construct(private readonly SkillRepositoryInterface $skills) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data): Skill
    {
        return $this->skills->create($data);
    }
}
