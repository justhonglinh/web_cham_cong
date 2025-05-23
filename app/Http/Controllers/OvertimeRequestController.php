<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        // Tìm yêu cầu overtime theo ID
        $requestData = OvertimeRequest::findOrFail($id);

        // Cập nhật trạng thái mới
        $requestData->status = $request->status;
        $requestData->save();

        // Nếu trạng thái mới là approved và trước đó chưa phải approved thì tăng current_registrations
        if ($request->status === 'approved') {
            $shift = $requestData->overtimeShift; // cần quan hệ overtimeShift trong model OvertimeRequest

            if ($shift) {
                $shift->current_registrations = $shift->current_registrations + 1;
                $shift->save();
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!')->with('delayReload', true);
    }

}
