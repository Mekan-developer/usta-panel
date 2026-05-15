<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'title.required' => 'Название проекта обязательно для заполнения.',
            'title.max' => 'Название проекта не должно превышать 255 символов.',
            'description.required' => 'Описание проекта обязательно для заполнения.',
            'type.required' => 'Тип проекта обязателен для заполнения.',
            'type' => 'Выбранный тип проекта недействителен.',
            'web_url.url' => 'Ссылка на веб-сайт должна быть корректным URL-адресом.',
            'web_url.max' => 'Ссылка на веб-сайт не должна превышать 500 символов.',
            'play_store_url.url' => 'Ссылка на Google Play должна быть корректным URL-адресом.',
            'play_store_url.max' => 'Ссылка на Google Play не должна превышать 500 символов.',
            'app_store_url.url' => 'Ссылка на App Store должна быть корректным URL-адресом.',
            'app_store_url.max' => 'Ссылка на App Store не должна превышать 500 символов.',
            'desktop_url.url' => 'Ссылка на десктоп-версию должна быть корректным URL-адресом.',
            'desktop_url.max' => 'Ссылка на десктоп-версию не должна превышать 500 символов.',
            'tech_stack.array' => 'Технологии должны быть переданы в виде массива.',
            'tech_stack.*.string' => 'Каждая технология должна быть строкой.',
            'tech_stack.*.max' => 'Название технологии не должно превышать 50 символов.',
            'is_active.boolean' => 'Поле активности должно быть булевым значением.',
            'is_private.boolean' => 'Поле приватности должно быть булевым значением.',
            'thumb_label.max' => 'Метка превью не должна превышать 100 символов.',
            'sort_order.integer' => 'Порядок сортировки должен быть целым числом.',
            'sort_order.min' => 'Порядок сортировки не может быть отрицательным.',
            'images.array' => 'Изображения должны быть переданы в виде массива.',
            'images.max' => 'Можно загрузить не более 20 изображений.',
            'images.*.image' => 'Каждый файл должен быть изображением.',
            'images.*.mimes' => 'Допустимые форматы изображений: jpeg, jpg, png, webp, gif.',
            'images.*.max' => 'Размер каждого изображения не должен превышать 10 МБ.',
        ];
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
