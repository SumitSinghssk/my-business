<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Requests\Concerns\NormalizesSlug;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BlogCategoryStoreRequest extends FormRequest
{
    use NormalizesSlug;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeSlug('name');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_categories', 'slug')->whereNull('deleted_at')],
            // Only live top-level categories can be parents (categories are two levels deep).
            'parent_id' => ['nullable', Rule::exists('blog_categories', 'id')->whereNull('parent_id')->whereNull('deleted_at')],
            'status' => ['required', 'string', new Enum(CommonStatusEnum::class)],
            'description' => ['nullable', 'string'],
            ...ImagePreset::get('category')->rules('image'),
        ];
    }

    public function messages(): array
    {
        return ImagePreset::get('category')->messages('image');
    }
}
