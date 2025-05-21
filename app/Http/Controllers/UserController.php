<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show()
    {
        $managerName = Auth::user()->name;

        return User::where('role', 'employee')
            ->where('manager', $managerName)
            ->get();
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = "employee";
        $user->manager = $request->manager;

        // Xử lý upload avatar nếu có
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); // Lưu vào storage/app/public/avatars
            $user->avatar = $avatarPath; // Giả sử bạn có trường avatar trong bảng users
        }

        $user->save();

        return Redirect::route('employees.management')->with('success', 'Tạo tài khoản thành công!');
    }

    public function update(Request $request, $id)
    {
        // Tìm user theo id
        $user = User::findOrFail($id);

        // Cập nhật thông tin user
        $user->name = $request->name;
        $user->email = $request->email;

        // Nếu có mật khẩu mới thì cập nhật
        if (!empty($validated['password'])) {
            $user->password = Hash::make($request->password);
        }

        // Xử lý upload avatar nếu có file mới
        if ($request->hasFile('avatar')) {
            // Xóa file avatar cũ nếu cần, ví dụ:
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu file mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('employees.management')->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Xóa avatar nếu có
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Xóa user thành công!');
    }
}
