<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends ProjectStoreRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($this->route('project')?->id)->whereNull('deleted_at')],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
