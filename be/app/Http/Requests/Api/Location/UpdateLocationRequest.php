<?php

namespace App\Http\Requests\Api\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'address'     => ['sometimes', 'required', 'string', 'max:500'],
            'latitude'    => ['sometimes', 'required', 'numeric', 'between:-90,90'],
            'longitude'   => ['sometimes', 'required', 'numeric', 'between:-180,180'],
            'radius'      => ['sometimes', 'required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
        ];
    }
}
