<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Models\Experience;

class DeleteExperienceAction
{
    public function __construct(private readonly ExperienceRepositoryInterface $experiences) {}

    public function execute(Experience $experience): void
    {
        $this->experiences->delete($experience);
    }
}
