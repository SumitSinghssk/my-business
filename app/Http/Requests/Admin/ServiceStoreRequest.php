<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Requests\Concerns\NormalizesSlug;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ServiceStoreRequest extends FormRequest
{
    use NormalizesSlug;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeSlug();

        $this->merge([
            // "What's included" is typed one item per line, tags comma-separated.
            'highlights' => self::toList($this->input('highlights'), "/\r\n|\r|\n/"),
            'tags' => self::toList($this->input('tags'), '/,/'),
        ]);
    }

    public static function toList(mixed $value, string $separator): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), 'strlen'));
        }

        return array_values(array_filter(array_map('trim', preg_split($separator, (string) $value)), 'strlen'));
    }

    public function messages(): array
    {
        return ImagePreset::get('service')->messages('featured_image');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')->whereNull('deleted_at')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'highlights' => ['array', 'max:12'],
            'highlights.*' => ['string', 'max:120'],
            'tags' => ['array', 'max:8'],
            'tags.*' => ['string', 'max:40'],
            'content' => ['required', 'string'],
            ...ImagePreset::get('service')->rules('featured_image'),
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status' => ['required', 'string', new Enum(CommonStatusEnum::class)],
        ];
    }
}
