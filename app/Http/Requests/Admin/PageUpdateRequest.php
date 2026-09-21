<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Requests\Concerns\NormalizesSlug;
use App\Models\Page;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PageUpdateRequest extends FormRequest
{
    use NormalizesSlug;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Normalise the slug (falling back to the title) so reserved/duplicate checks apply to the final value.
        $this->normalizeSlug();
    }

    public function messages(): array
    {
        return ImagePreset::get('page')->messages('featured_image') + [
            'slug.not_in' => 'This URL is already used by a built-in page. Please choose another slug.',
        ];
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::notIn(Page::RESERVED_SLUGS), Rule::unique('pages', 'slug')->ignore($pageId)->whereNull('deleted_at')],
            'content' => ['required', 'string'],
            ...ImagePreset::get('page')->rules('featured_image'),
            'remove_image' => ['nullable', 'boolean'],
            'status' => ['required', 'string', new Enum(CommonStatusEnum::class)],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
