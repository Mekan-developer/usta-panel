<?php

namespace App\Models;

use App\Enums\LearningCategory;
use App\Enums\LearningStatus;
use Database\Factories\LearningTopicFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningTopic extends Model
{
    /** @use HasFactory<LearningTopicFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'status',
        'period',
        'progress',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'category' => LearningCategory::class,
            'status' => LearningStatus::class,
            'progress' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
