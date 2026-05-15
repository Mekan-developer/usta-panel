<?php

namespace App\Repositories;

use App\Contracts\Repositories\LearningTopicRepositoryInterface;
use App\Models\LearningTopic;
use Illuminate\Support\Collection;

class LearningTopicRepository implements LearningTopicRepositoryInterface
{
    /** @return Collection<int, LearningTopic> */
    public function all(): Collection
    {
        return LearningTopic::orderBy('sort_order')->orderBy('created_at')->get();
    }

    /**
     * @return array<string, Collection<int, LearningTopic>>
     */
    public function groupedByStatus(): array
    {
        return LearningTopic::orderBy('sort_order')
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn (LearningTopic $topic) => $topic->status->value)
            ->all();
    }

    public function find(int $id): LearningTopic
    {
        return LearningTopic::findOrFail($id);
    }

    public function create(array $data): LearningTopic
    {
        return LearningTopic::create($data);
    }

    public function update(LearningTopic $topic, array $data): void
    {
        $topic->update($data);
    }

    public function delete(LearningTopic $topic): void
    {
        $topic->delete();
    }
}
