<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function getEmployees()
    {
        return User::where('role', 'employee')->get();
    }

    public function store(Request $request)
    {
//        dd($request->all());
        // Validate dữ liệu
        // Tạo người dùng mới
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Mã hóa mật khẩu

        // Hardcoded values for testing
        $user->id_manager = 1;   // Assuming "1" is the manager's ID
        $user->id_position = 2;  // Assuming "2" is the position ID for "developer"
        $user->role = "employee"; // Gán role mặc định

        $user->save();

        // Quay lại trang dashboard với thông báo
        return Redirect::route('dashboard')->with('success', 'Tạo tài khoản thành công!');

    }

}
