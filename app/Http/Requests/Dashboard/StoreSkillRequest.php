<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\SkillCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'abbr' => ['required', 'string', 'max:4'],
            'category' => ['required', 'string', new Enum(SkillCategory::class)],
            'percent' => ['required', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }
}
