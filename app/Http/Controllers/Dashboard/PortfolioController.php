<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Portfolio\DeleteProjectAction;
use App\Actions\Portfolio\DeleteProjectImageAction;
use App\Actions\Portfolio\StoreProjectAction;
use App\Actions\Portfolio\StoreProjectImagesAction;
use App\Actions\Portfolio\UpdateProjectAction;
use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Enums\ProjectType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreProjectRequest;
use App\Http\Requests\Dashboard\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly ProjectRepositoryInterface $projects,
        private readonly StoreProjectAction $storeProject,
        private readonly UpdateProjectAction $updateProject,
        private readonly DeleteProjectAction $deleteProject,
        private readonly StoreProjectImagesAction $storeImages,
        private readonly DeleteProjectImageAction $deleteImage,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Portfolio/Index', [
            'projects' => $this->projects->allWithImages(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Portfolio/Create', [
            'types' => array_column(ProjectType::cases(), 'value'),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->storeProject->execute($request->safe()->except('images'));

        if ($request->hasFile('images')) {
            $this->storeImages->execute($project, $request->file('images'));
        }

        $this->notifySuccess('notifications.created', ['resource' => __('resources.project')]);

        return redirect()->route('dashboard.portfolio.index');
    }

    public function edit(int $id): Response
    {
        $project = Project::findOrFail($id);

        return Inertia::render('Dashboard/Portfolio/Edit', [
            'project' => $this->projects->loadImages($project),
            'types' => array_column(ProjectType::cases(), 'value'),
        ]);
    }

    public function update(UpdateProjectRequest $request, int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $this->updateProject->execute($project, $request->safe()->except('images'));

        if ($request->hasFile('images')) {
            $this->storeImages->execute($project, $request->file('images'));
        }

        $this->notifySuccess('notifications.updated', ['resource' => __('resources.project')]);

        return redirect()->route('dashboard.portfolio.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $this->deleteProject->execute($project);

        $this->notifySuccess('notifications.deleted', ['resource' => __('resources.project')]);

        return redirect()->route('dashboard.portfolio.index');
    }

    public function destroyImage(int $imageId): RedirectResponse
    {
        $image = ProjectImage::findOrFail($imageId);
        $this->deleteImage->execute($image);

        $this->notifySuccess('notifications.image_deleted');

        return back();
    }
}
