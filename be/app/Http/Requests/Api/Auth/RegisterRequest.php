<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Vui lòng nhập họ tên.',
            'email.required'                 => 'Vui lòng nhập email.',
            'email.email'                    => 'Email không hợp lệ.',
            'email.unique'                   => 'Email đã được sử dụng.',
            'password.required'              => 'Vui lòng nhập mật khẩu.',
            'password.min'                   => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed'             => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
        ];
    }
}
