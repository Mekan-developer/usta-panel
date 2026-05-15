<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:200'],
            'duration' => ['required', 'string', 'max:50'],
            'role' => ['required', 'array'],
            'role.tk' => ['required', 'string', 'max:200'],
            'role.ru' => ['required', 'string', 'max:200'],
            'role.en' => ['nullable', 'string', 'max:200'],
            'description' => ['required', 'array'],
            'description.tk' => ['required', 'string', 'max:3000'],
            'description.ru' => ['required', 'string', 'max:3000'],
            'description.en' => ['nullable', 'string', 'max:3000'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }
}
