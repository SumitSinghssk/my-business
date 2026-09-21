<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Requests\Concerns\NormalizesSlug;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BlogCategoryUpdateRequest extends FormRequest
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
        $categoryId = $this->route('blog_category')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_categories', 'slug')->ignore($categoryId)->whereNull('deleted_at')],
            // Only live top-level categories can be parents, and a category that has
            // sub-categories must stay top-level (otherwise categories could form a loop).
            'parent_id' => [
                'nullable',
                Rule::exists('blog_categories', 'id')->whereNull('parent_id')->whereNull('deleted_at'),
                Rule::notIn([$categoryId]),
                Rule::prohibitedIf(fn () => $this->route('blog_category')->children()->exists()),
            ],
            'status' => ['required', new Enum(CommonStatusEnum::class)],
            'description' => ['nullable', 'string'],
            ...ImagePreset::get('category')->rules('image'),
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return ImagePreset::get('category')->messages('image');
    }
}
