<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Normalise the slug (falling back to the title) so reserved/duplicate checks apply to the final value.
        $this->merge(['slug' => str()->slug($this->input('slug') ?: (string) $this->input('title'))]);
    }

    public function messages(): array
    {
        return ['slug.not_in' => 'This URL is already used by a built-in page. Please choose another slug.'];
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::notIn(Page::RESERVED_SLUGS), Rule::unique('pages', 'slug')->whereNull('deleted_at')],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'status' => ['required', 'string', new Enum(CommonStatusEnum::class)],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
