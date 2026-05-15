<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectObserver
{
    public function deleting(Project $project): void
    {
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
    }
}
