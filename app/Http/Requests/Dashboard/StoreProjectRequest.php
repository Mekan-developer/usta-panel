<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
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
            'description' => ['required', 'string'],
            'type' => ['required', new Enum(ProjectType::class)],
            'web_url' => ['nullable', 'url', 'max:500'],
            'play_store_url' => ['nullable', 'url', 'max:500'],
            'app_store_url' => ['nullable', 'url', 'max:500'],
            'desktop_url' => ['nullable', 'url', 'max:500'],
            'tech_stack' => ['nullable', 'array'],
            'tech_stack.*' => ['string', 'max:50'],
            'is_active' => ['boolean'],
            'is_private' => ['boolean'],
            'thumb_label' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp,gif', 'max:10240'],
        ];
    }
}
