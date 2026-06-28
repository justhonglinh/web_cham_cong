<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    /**
     * Lấy danh sách nhân viên do manager hiện tại quản lý (có phân trang).
     */
    public function index()
    {
        $managerId = Auth::user()->id;

        $employees = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->with(['details'])
            ->paginate(15);

        return response()->json($employees);
    }

    /**
     * Tạo mới nhân viên thuộc manager hiện tại.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'employee',
            'manager'  => Auth::user()->id,
        ]);

        return response()->json([
            'message' => 'Tạo tài khoản nhân viên thành công.',
            'user'    => $user,
        ], 201);
    }

    /**
     * Cập nhật thông tin nhân viên.
     */
    public function update(Request $request, $id)
    {
        $managerId = Auth::user()->id;

        $user = User::where('id', $id)
            ->where('manager', $managerId)
            ->firstOrFail();

        $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|string|min:6',
        ]);

        $data = $request->only(['name', 'email']);

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Cập nhật tài khoản nhân viên thành công.',
            'user'    => $user,
        ]);
    }

    /**
     * Xóa nhân viên.
     */
    public function destroy($id)
    {
        $managerId = Auth::user()->id;

        $user = User::where('id', $id)
            ->where('manager', $managerId)
            ->firstOrFail();

        $user->delete();

        return response()->json([
            'message' => 'Xóa tài khoản nhân viên thành công.',
        ]);
    }
}
