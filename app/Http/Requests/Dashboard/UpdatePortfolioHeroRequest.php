<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioHeroRequest extends FormRequest
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
            'role' => ['required', 'array'],
            'role.tk' => ['required', 'string', 'max:200'],
            'role.ru' => ['required', 'string', 'max:200'],
            'role.en' => ['nullable', 'string', 'max:200'],
            'bio' => ['required', 'array'],
            'bio.tk' => ['required', 'string', 'max:2000'],
            'bio.ru' => ['required', 'string', 'max:2000'],
            'bio.en' => ['nullable', 'string', 'max:2000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'location' => ['required', 'array'],
            'location.tk' => ['required', 'string', 'max:200'],
            'location.ru' => ['required', 'string', 'max:200'],
            'location.en' => ['nullable', 'string', 'max:200'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
        ];
    }
}
