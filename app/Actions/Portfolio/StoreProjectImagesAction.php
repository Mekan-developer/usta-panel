<?php

namespace App\Actions\Portfolio;

use App\Jobs\ProcessProjectImageJob;
use App\Models\Project;
use Illuminate\Http\UploadedFile;

class StoreProjectImagesAction
{
    public function execute(Project $project, array $files): void
    {
        $nextOrder = $project->images()->max('order') ?? 0;

        foreach ($files as $index => $file) {
            /** @var UploadedFile $file */
            $tempPath = $file->store('temp/project-images');

            $image = $project->images()->create([
                'path' => $tempPath,
                'order' => $nextOrder + $index + 1,
                'is_processing' => true,
            ]);

            ProcessProjectImageJob::dispatch($image, $tempPath);
        }
    }
}
