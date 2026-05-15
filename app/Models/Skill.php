<?php

namespace App\Models;

use App\Enums\SkillCategory;
use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    /** @use HasFactory<SkillFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'abbr',
        'category',
        'percent',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'category' => SkillCategory::class,
            'percent' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
