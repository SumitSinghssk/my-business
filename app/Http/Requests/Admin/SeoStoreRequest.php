<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesSeoPath;
use App\Models\Seo;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeoStoreRequest extends FormRequest
{
    use NormalizesSeoPath;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('seos', 'slug'),
            ],

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',

            ...ImagePreset::get('og')->rules('og_image'),

            'schema' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (filled($value) && Seo::parseSchema($value) === null) {
                    $fail('The schema must be valid JSON-LD (plain JSON, or JSON inside <script type="application/ld+json"> tags).');
                }
            }],
            'faqs' => 'nullable|string',

            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'index' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return ImagePreset::get('og')->messages('og_image');
    }
}
