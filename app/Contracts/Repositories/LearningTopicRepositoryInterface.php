<?php

namespace App\Contracts\Repositories;

use App\Models\LearningTopic;
use Illuminate\Support\Collection;

interface LearningTopicRepositoryInterface
{
    /** @return Collection<int, LearningTopic> */
    public function all(): Collection;

    /**
     * @return array<string, Collection<int, LearningTopic>>
     */
    public function groupedByStatus(): array;

    public function find(int $id): LearningTopic;

    public function create(array $data): LearningTopic;

    public function update(LearningTopic $topic, array $data): void;

    public function delete(LearningTopic $topic): void;
}
