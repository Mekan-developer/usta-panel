<?php

namespace App\Jobs;

use App\Models\ProjectImage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProcessProjectImageJob implements ShouldQueue
{
    use Queueable;

    public string $queue = 'images';

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public readonly ProjectImage $image,
        public readonly string $tempPath
    ) {}

    public function handle(): void
    {
        try {
            $data = Storage::get($this->tempPath);

            if ($data === null) {
                Log::warning("ProcessProjectImageJob: temp file not found: {$this->tempPath}");
                $this->image->delete();

                return;
            }

            $gd = @imagecreatefromstring($data);

            if ($gd === false) {
                Log::warning("ProcessProjectImageJob: invalid image for project_image #{$this->image->id}");
                $this->image->delete();
                Storage::delete($this->tempPath);

                return;
            }

            $srcWidth = imagesx($gd);

            if ($srcWidth > 1200) {
                $scaled = imagescale($gd, 1200, -1);
                imagedestroy($gd);
                $gd = $scaled;
            }

            $dir = 'projects/'.$this->image->project_id;
            $filename = Str::random(16).'.webp';
            $finalPath = $dir.'/'.$filename;

            Storage::disk('public')->makeDirectory($dir);

            imagewebp($gd, Storage::disk('public')->path($finalPath), 85);
            imagedestroy($gd);

            $this->image->update([
                'path' => $finalPath,
                'is_processing' => false,
            ]);

            Storage::delete($this->tempPath);
        } catch (Throwable $e) {
            Log::error("ProcessProjectImageJob failed for project_image #{$this->image->id}: {$e->getMessage()}");
            throw $e;
        }
    }
}
