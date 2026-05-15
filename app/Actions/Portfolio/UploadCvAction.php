<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\SettingRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadCvAction
{
    public function __construct(private readonly SettingRepositoryInterface $settings) {}

    public function execute(UploadedFile $file): string
    {
        $oldPath = $this->settings->get('cv_path');

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $path = $file->storeAs('cv', 'cv.'.$file->getClientOriginalExtension(), 'public');

        $this->settings->set('cv_path', $path);

        return $path;
    }
}
