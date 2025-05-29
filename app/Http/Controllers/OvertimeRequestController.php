<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
    // Nhân viên xác nhận tham gia hoặc từ chối ca OT
    public function respond(Request $request)
    {
        $request->validate([
            'overtime_shift_id' => 'required|exists:overtime_shifts,id',
            'status' => 'required|in:accepted,declined',
        ]);

        // Tìm hoặc tạo mới bản ghi đăng ký OT cho user với ca này
        OvertimeRequest::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'overtime_shift_id' => $request->overtime_shift_id,
            ],
            [
                'status' => $request->status,
            ]
        );

        return redirect()->route('overtime.index')->with('success', 'Cập nhật trạng thái OT thành công!');
    }
}