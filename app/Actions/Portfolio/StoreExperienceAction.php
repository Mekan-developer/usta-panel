<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Models\Experience;

class StoreExperienceAction
{
    public function __construct(private readonly ExperienceRepositoryInterface $experiences) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data): Experience
    {
        return $this->experiences->create($data);
    }
}
