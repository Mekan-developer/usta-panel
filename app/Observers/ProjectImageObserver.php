<?php

namespace App\Observers;

use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;

class ProjectImageObserver
{
    public function deleting(ProjectImage $projectImage): void
    {
        Storage::disk('public')->delete($projectImage->path);
    }
}
