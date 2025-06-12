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
        $managerId = Auth::user()->id;
        $employees = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->with(['manager', 'details'])
            ->get()
            ->map(function($e) {
                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'email' => $e->email,
                    'avatar' => $e->avatar,
                    'manager' => $e->manager,
                    'created_at' => $e->created_at ? $e->created_at->toIso8601String() : '',
                    'details' => $e->details ? [
                        'phone' => $e->details->phone,
                        'address' => $e->details->address,
                        'birthday' => $e->details->birthday,
                        'emergency_contact' => $e->details->emergency_contact,
                    ] : ['phone'=>'','address'=>'','birthday'=>'','emergency_contact'=>''],
                ];
            })->values();

        return view('employees_management', ['employees' => $employees]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // Nhận từ khóa tìm kiếm

        // Thực hiện tìm kiếm trong database
        $results = User::where('column_name', 'LIKE', '%' . $query . '%')->get();

        // Trả kết quả về dạng JSON
        return response()->json($results);
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

        return Redirect::route('employees.index')->with('success', 'Tạo tài khoản thành công!');
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

        return redirect()->route('employees.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Xóa avatar nếu có
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();

        return redirect()->route('employees.index')->with('success', 'Xóa tài khoản thành công!');
    }
}
