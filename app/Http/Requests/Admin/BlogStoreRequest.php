<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Requests\Concerns\NormalizesSlug;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BlogStoreRequest extends FormRequest
{
    use NormalizesSlug;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeSlug();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blogs', 'slug')->whereNull('deleted_at')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            ...ImagePreset::get('blog')->rules('featured_image'),
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:blog_categories,id'],
            'status' => ['required', 'string', new Enum(CommonStatusEnum::class)],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return ImagePreset::get('blog')->messages('featured_image');
    }
}
