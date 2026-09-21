<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class ServiceUpdateRequest extends ServiceStoreRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')->ignore($this->route('service')?->id)->whereNull('deleted_at')],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
