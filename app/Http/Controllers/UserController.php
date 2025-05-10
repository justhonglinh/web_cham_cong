<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getEmployees()
    {
        return User::where('role', 'employee')->get();
    }

    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',  // Mật khẩu phải có ít nhất 8 ký tự và xác nhận
            'position' => 'required|string',  // Giả sử position là một giá trị chuỗi (ví dụ: 'manager', 'employee')
        ]);
        dd("hello");
        // Tạo người dùng mới
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Mã hóa mật khẩu
        $user->role = $request->position; // Gán role từ position
        $user->save();

        // Quay lại trang dashboard với thông báo
        return redirect()->route('dashboard')->with('success', 'User created successfully');
    }
}
