<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public const SERVICES = [
        'website' => 'Website Development',
        'web-app' => 'Web Application',
        'mobile-app' => 'Mobile App',
        'custom-software' => 'Custom Software',
        'ui-ux' => 'UI/UX Design',
        'cloud-devops' => 'Cloud & DevOps',
        'consulting' => 'Architecture Consulting',
        'other' => 'Something Else',
    ];

    public const BUDGETS = [
        'under-10k' => 'Under $10k',
        '10k-25k' => '$10k – $25k',
        '25k-50k' => '$25k – $50k',
        '50k-100k' => '$50k – $100k',
        '100k-plus' => '$100k+',
        'not-sure' => 'Not sure yet',
    ];

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
            'service' => ['nullable', 'string', 'in:'.implode(',', array_keys(self::SERVICES))],
            'budget' => ['nullable', 'string', 'in:'.implode(',', array_keys(self::BUDGETS))],
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
