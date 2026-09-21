<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Requests\Concerns\NormalizesSlug;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProjectStoreRequest extends FormRequest
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
            'tags' => ServiceStoreRequest::toList($this->input('tags'), '/,/'),
            'results' => self::toResults($this->input('results')),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    /**
     * Results are typed one per line as "value | label", e.g. "64% | lower cloud spend".
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function toResults(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn ($row) => is_array($row) && filled($row['value'] ?? null)));
        }

        return collect(preg_split("/\r\n|\r|\n/", (string) $value))
            ->map(fn ($line) => array_map('trim', explode('|', $line, 2)))
            ->filter(fn ($parts) => $parts[0] !== '')
            ->map(fn ($parts) => ['value' => $parts[0], 'label' => $parts[1] ?? ''])
            ->values()
            ->all();
    }

    public function messages(): array
    {
        return ImagePreset::get('project')->messages('featured_image') + [
            'results.*.value.max' => 'Each result value may not be longer than 20 characters.',
        ];
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')->whereNull('deleted_at')],
            'client' => ['nullable', 'string', 'max:150'],
            'industry' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'digits:4'],
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')->whereNull('deleted_at')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'results' => ['array', 'max:4'],
            'results.*.value' => ['required', 'string', 'max:20'],
            'results.*.label' => ['nullable', 'string', 'max:80'],
            'tags' => ['array', 'max:8'],
            'tags.*' => ['string', 'max:40'],
            'content' => ['required', 'string'],
            ...ImagePreset::get('project')->rules('featured_image'),
            'project_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status' => ['required', 'string', new Enum(CommonStatusEnum::class)],
        ];
    }
}
