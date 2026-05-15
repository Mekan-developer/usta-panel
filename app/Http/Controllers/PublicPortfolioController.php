<?php

namespace App\Http\Controllers;

use App\Actions\Portfolio\CheckPrivatePasswordAction;
use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Http\Requests\Contact\StoreContactMessageRequest;
use App\Http\Requests\Portfolio\UnlockPrivateRequest;
use App\Models\Experience;
use App\Models\Project;
use App\Services\ContactMessageService;
use App\Support\PortfolioSettings;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class PublicPortfolioController extends Controller
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects,
        private readonly SkillRepositoryInterface $skills,
        private readonly ExperienceRepositoryInterface $experiences,
        private readonly SettingRepositoryInterface $settings,
        private readonly CheckPrivatePasswordAction $checkPassword,
        private readonly ContactMessageService $contactMessages,
    ) {}

    public function show(): Response
    {
        $settings = $this->resolveSettings();

        return Inertia::render('Public/Portfolio', [
            'settings' => $settings,
            'skills' => $this->skills->groupedByCategory(),
            'experiences' => $this->experiences->all()->map(fn (Experience $e) => [
                'id' => $e->id,
                'company' => $e->company,
                'duration' => $e->duration,
                'role' => $e->role,
                'description' => $e->description,
            ])->all(),
            'projects' => $this->projects->publicActive()->map(
                fn (Project $project) => $this->presentProject($project, locked: $project->is_private)
            )->values()->all(),
        ]);
    }

    public function unlock(UnlockPrivateRequest $request): JsonResponse
    {
        if (! $this->checkPassword->execute($request->validated('password'))) {
            return response()->json([
                'ok' => false,
                'message' => __('portfolio_public.password.wrong'),
            ], 422);
        }

        $unlocked = $this->projects->publicActive()
            ->where('is_private', true)
            ->map(fn (Project $project) => $this->presentProject($project, locked: false))
            ->values()
            ->all();

        return response()->json([
            'ok' => true,
            'projects' => $unlocked,
        ]);
    }

    public function sendMessage(StoreContactMessageRequest $request): JsonResponse
    {
        $this->contactMessages->store($request->validated());

        return response()->json(['ok' => true]);
    }

    /**
     * @return array<string, mixed>
     */
    private function presentProject(Project $project, bool $locked): array
    {
        $base = [
            'id' => $project->id,
            'is_private' => $project->is_private,
            'locked' => $locked,
        ];

        if ($locked) {
            return $base;
        }

        return $base + [
            'title' => $project->title,
            'title_translations' => $project->title_translations,
            'description' => $project->description,
            'description_translations' => $project->description_translations,
            'thumb_label' => $project->thumb_label,
            'tech_stack' => $project->tech_stack ?? [],
            'web_url' => $project->web_url,
            'play_store_url' => $project->play_store_url,
            'app_store_url' => $project->app_store_url,
            'desktop_url' => $project->desktop_url,
            'images' => $project->images
                ->where('is_processing', false)
                ->sortBy('order')
                ->map(fn ($img) => ['url' => asset('storage/'.$img->path)])
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveSettings(): array
    {
        $raw = $this->settings->getMany(PortfolioSettings::publicKeys());
        $translatable = PortfolioSettings::TRANSLATABLE;

        $result = [];
        foreach ($raw as $key => $value) {
            $short = str_replace('portfolio.', '', $key);

            if ($value !== null && in_array($key, $translatable, true)) {
                $decoded = json_decode($value, true);
                $result[$short] = is_array($decoded) ? $decoded : $value;

                continue;
            }

            $result[$short] = $value;
        }

        return $result;
    }
}
