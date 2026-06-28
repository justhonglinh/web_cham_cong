<?php

namespace App\Http\Requests\Api\Overtime;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOvertimeShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['sometimes', 'required', 'string', 'max:255'],
            'start_time'        => ['sometimes', 'required', 'date_format:H:i'],
            'end_time'          => ['sometimes', 'required', 'date_format:H:i', 'after:start_time'],
            'date'              => ['sometimes', 'required', 'date'],
            'max_registrations' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
