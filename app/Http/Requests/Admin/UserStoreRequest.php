<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommonStatusEnum;
use App\Support\ImagePreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', new Enum(CommonStatusEnum::class)],
            'bio' => ['nullable', 'string', 'max:1000'],
            ...ImagePreset::get('avatar')->rules('avatar'),

            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],

            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return ImagePreset::get('avatar')->messages('avatar');
    }
}
