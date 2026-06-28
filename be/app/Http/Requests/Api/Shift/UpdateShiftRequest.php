<?php

namespace App\Http\Requests\Api\Shift;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['sometimes', 'required', 'string', 'max:255'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time'   => ['sometimes', 'required', 'date_format:H:i', 'after:start_time'],
        ];
    }
}
