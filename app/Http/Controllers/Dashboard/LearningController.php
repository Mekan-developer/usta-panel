<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreLearningTopicRequest;
use App\Http\Requests\Dashboard\UpdateLearningTopicRequest;
use App\Models\LearningTopic;
use App\Services\LearningTopicService;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LearningController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly LearningTopicService $service,
    ) {}

    public function index(): Response
    {
        $grouped = $this->service->getGroupedByStatus();

        return Inertia::render('Dashboard/Learning/Index', [
            'topics' => [
                'studied' => array_values(($grouped['studied'] ?? collect())->toArray()),
                'current' => array_values(($grouped['current'] ?? collect())->toArray()),
                'planned' => array_values(($grouped['planned'] ?? collect())->toArray()),
            ],
        ]);
    }

    public function store(StoreLearningTopicRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());
        $this->notifySuccess('notifications.learning.created');

        return redirect()->route('dashboard.learning.index');
    }

    public function update(UpdateLearningTopicRequest $request, LearningTopic $topic): RedirectResponse
    {
        $this->service->update($topic, $request->validated());
        $this->notifySuccess('notifications.learning.updated');

        return redirect()->route('dashboard.learning.index');
    }

    public function destroy(LearningTopic $topic): RedirectResponse
    {
        $this->service->delete($topic);
        $this->notifySuccess('notifications.learning.deleted');

        return redirect()->route('dashboard.learning.index');
    }
}
