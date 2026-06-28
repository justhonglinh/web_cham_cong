<?php

namespace App\Http\Requests\Api\Overtime;

use Illuminate\Foundation\Http\FormRequest;

class StoreOvertimeShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'start_time'        => ['required', 'date_format:H:i'],
            'end_time'          => ['required', 'date_format:H:i', 'after:start_time'],
            'date'              => ['required', 'date'],
            'max_registrations' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
