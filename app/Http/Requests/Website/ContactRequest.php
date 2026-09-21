<?php

namespace App\Http\Requests\Website;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    /**
     * Options for "What do you need?": active services (slug => title) plus a catch-all.
     *
     * @return array<string, string>
     */
    public static function serviceOptions(): array
    {
        return Service::active()->ordered()->pluck('title', 'slug')->all() + ['other' => 'Something Else'];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s().]{6,30}$/'],
            'service' => ['nullable', 'string', Rule::in(array_keys(self::serviceOptions()))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            // Honeypot: hidden from people, bots fill it in.
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid phone number.',
            'message.min' => 'Please tell us a little more about your project (at least 10 characters).',
        ];
    }
}
