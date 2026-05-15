<?php

namespace App\Actions\Portfolio;

use App\Models\ProjectImage;

class DeleteProjectImageAction
{
    public function execute(ProjectImage $image): void
    {
        $image->delete();
    }
}
