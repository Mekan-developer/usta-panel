<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\LearningCategory;
use App\Enums\LearningStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLearningTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(LearningCategory::class)],
            'status' => ['required', Rule::enum(LearningStatus::class)],
            'period' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
