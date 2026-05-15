<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Models\Experience;

class UpdateExperienceAction
{
    public function __construct(private readonly ExperienceRepositoryInterface $experiences) {}

    /** @param array<string, mixed> $data */
    public function execute(Experience $experience, array $data): void
    {
        $this->experiences->update($experience, $data);
    }
}
