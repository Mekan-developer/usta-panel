<?php

namespace App\Models;

use App\Enums\ProjectType;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'title_translations',
        'description',
        'description_translations',
        'type',
        'thumbnail',
        'thumb_label',
        'screenshots',
        'web_url',
        'play_store_url',
        'app_store_url',
        'desktop_url',
        'tech_stack',
        'is_active',
        'is_private',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => ProjectType::class,
            'screenshots' => 'array',
            'tech_stack' => 'array',
            'title_translations' => 'array',
            'description_translations' => 'array',
            'is_active' => 'boolean',
            'is_private' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function localizedTitle(string $locale, string $fallback = 'ru'): string
    {
        $translations = $this->title_translations ?? [];

        return $translations[$locale] ?? $translations[$fallback] ?? $this->title;
    }

    public function localizedDescription(string $locale, string $fallback = 'ru'): string
    {
        $translations = $this->description_translations ?? [];

        return $translations[$locale] ?? $translations[$fallback] ?? $this->description;
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }
}
