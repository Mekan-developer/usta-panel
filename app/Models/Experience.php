<?php

namespace App\Models;

use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    /** @use HasFactory<ExperienceFactory> */
    use HasFactory;

    protected $fillable = [
        'company',
        'duration',
        'role',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'role' => 'array',
            'description' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function localizedRole(string $locale, string $fallback = 'ru'): string
    {
        return $this->role[$locale] ?? $this->role[$fallback] ?? '';
    }

    public function localizedDescription(string $locale, string $fallback = 'ru'): string
    {
        return $this->description[$locale] ?? $this->description[$fallback] ?? '';
    }
}
