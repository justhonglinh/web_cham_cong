<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        // Tìm yêu cầu overtime theo ID
        $requestData = OvertimeRequest::findOrFail($id);

        // Lưu trạng thái cũ để kiểm tra
        $oldStatus = $requestData->status;

        // Cập nhật trạng thái mới
        $requestData->status = $request->status;
        $requestData->save();

        // Nếu trạng thái mới là approved và trước đó chưa phải approved
        if ($request->status === 'approved' && $oldStatus !== 'approved') {
            $shift = $requestData->overtimeShift;

            if ($shift) {
                // Tăng số lượng đăng ký hiện tại
                $shift->current_registrations = $shift->current_registrations + 1;
                $shift->save();

                // Tạo bản ghi attendance cho overtime
                $this->createOvertimeAttendance($requestData);
            }
        }

        // Nếu trạng thái mới là rejected và trước đó là approved
        if ($request->status === 'rejected' && $oldStatus === 'approved') {
            $shift = $requestData->overtimeShift;

            if ($shift) {
                // Giảm số lượng đăng ký hiện tại
                $shift->current_registrations = max(0, $shift->current_registrations - 1);
                $shift->save();

                // Xóa bản ghi attendance overtime nếu có
                $this->removeOvertimeAttendance($requestData);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!')->with('delayReload', true);
    }

    /**
     * Tạo bản ghi attendance cho overtime
     */
    private function createOvertimeAttendance(OvertimeRequest $overtimeRequest)
    {
        $shift = $overtimeRequest->overtimeShift;
        $user = $overtimeRequest->user;

        // Kiểm tra xem đã có attendance cho overtime này chưa
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('overtime_id', $shift->id)
            ->where('date', $shift->date)
            ->first();

        if (!$existingAttendance) {
            // Tạo bản ghi attendance mới cho overtime
            Attendance::create([
                'user_id' => $user->id,
                'overtime_id' => $shift->id,
                'shift_id' => null, // Không có shift_id cho overtime
                'date' => $shift->date,
                'check_in_time' => null, // Sẽ được cập nhật khi nhân viên chấm công
                'check_out_time' => null, // Sẽ được cập nhật khi nhân viên chấm công
                'status' => 'absent', // Trạng thái chờ chấm công
                'face_image' => null, // Sẽ được cập nhật khi chấm công
            ]);
        }
    }

    /**
     * Xóa bản ghi attendance cho overtime
     */
    private function removeOvertimeAttendance(OvertimeRequest $overtimeRequest)
    {
        $shift = $overtimeRequest->overtimeShift;
        $user = $overtimeRequest->user;

        // Tìm và xóa bản ghi attendance overtime
        $attendance = Attendance::where('user_id', $user->id)
            ->where('overtime_id', $shift->id)
            ->where('date', $shift->date)
            ->first();

        if ($attendance) {
            $attendance->delete();
        }
    }
}
