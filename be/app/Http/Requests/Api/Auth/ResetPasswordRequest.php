<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'                 => ['required', 'string'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required'                  => 'Thiếu mã đặt lại mật khẩu.',
            'email.required'                  => 'Vui lòng nhập email.',
            'email.email'                     => 'Email không hợp lệ.',
            'password.required'               => 'Vui lòng nhập mật khẩu mới.',
            'password.min'                     => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed'              => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required'  => 'Vui lòng xác nhận mật khẩu.',
        ];
    }
}
