<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'password' => ['nullable', 'string', 'min:6', 'max:128', 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
            'accent' => ['required', 'string', 'in:indigo,violet,teal,amber'],
            'theme' => ['required', 'string', 'in:dark,light'],
        ];
    }
}
