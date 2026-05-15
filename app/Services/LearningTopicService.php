<?php

namespace App\Services;

use App\Contracts\Repositories\LearningTopicRepositoryInterface;
use App\Models\LearningTopic;
use Illuminate\Support\Collection;

class LearningTopicService
{
    public function __construct(
        private readonly LearningTopicRepositoryInterface $repository,
    ) {}

    /**
     * @return array<string, Collection<int, LearningTopic>>
     */
    public function getGroupedByStatus(): array
    {
        return $this->repository->groupedByStatus();
    }

    public function store(array $data): LearningTopic
    {
        return $this->repository->create($data);
    }

    public function update(LearningTopic $topic, array $data): void
    {
        $this->repository->update($topic, $data);
    }

    public function delete(LearningTopic $topic): void
    {
        $this->repository->delete($topic);
    }
}
