<?php

namespace App\Http\Requests\Api\Overtime;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOvertimeRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:approved,rejected'],
        ];
    }
}
