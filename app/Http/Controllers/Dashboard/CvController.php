<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Portfolio\UploadCvAction;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UploadCvRequest;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CvController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly SettingRepositoryInterface $settings,
        private readonly UploadCvAction $uploadCv,
    ) {}

    public function index(): Response
    {
        $cvPath = $this->settings->get('cv_path');

        return Inertia::render('Dashboard/Portfolio/Cv', [
            'cvUrl' => $cvPath && Storage::disk('public')->exists($cvPath)
                ? Storage::disk('public')->url($cvPath)
                : null,
        ]);
    }

    public function store(UploadCvRequest $request): RedirectResponse
    {
        $this->uploadCv->execute($request->file('cv'));

        $this->notifySuccess('notifications.cv_uploaded');

        return redirect()->route('dashboard.cv.index');
    }

    public function destroy(): RedirectResponse
    {
        $cvPath = $this->settings->get('cv_path');

        if ($cvPath && Storage::disk('public')->exists($cvPath)) {
            Storage::disk('public')->delete($cvPath);
        }

        $this->settings->set('cv_path', null);

        $this->notifySuccess('notifications.cv_deleted');

        return redirect()->route('dashboard.cv.index');
    }
}
