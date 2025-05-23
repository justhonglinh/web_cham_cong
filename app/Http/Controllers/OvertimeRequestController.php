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

        // Kiểm tra và cập nhật trạng thái
        $requestData->status = $request->status;

        $requestData->save(); // Lưu thay đổi

        // Trở về trang trước đó với thông báo thành công
        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!')->with('delayReload', true);
    }
}
