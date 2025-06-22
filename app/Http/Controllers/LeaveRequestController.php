<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    // Hiển thị form đăng ký cho nhân viên
    public function showForm()
    {
        return view('employees.leave-request');
    }

    // Nhân viên tạo yêu cầu mới
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:late,leave,early_leave',
            'request_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:500',
        ], [
            'type.required' => 'Vui lòng chọn loại yêu cầu.',
            'type.in' => 'Loại yêu cầu không hợp lệ.',
            'request_date.required' => 'Vui lòng chọn ngày yêu cầu.',
            'request_date.after_or_equal' => 'Ngày yêu cầu phải từ hôm nay trở đi.',
            'start_time.date_format' => 'Thời gian bắt đầu không đúng định dạng.',
            'end_time.date_format' => 'Thời gian kết thúc không đúng định dạng.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'reason.required' => 'Vui lòng nhập lý do.',
            'reason.max' => 'Lý do không được quá 500 ký tự.',
        ]);

        // Validation bổ sung cho thời gian
        if ($request->type === 'late' || $request->type === 'early_leave') {
            if (!$request->start_time || !$request->end_time) {
                return back()->withErrors(['error' => 'Vui lòng nhập đầy đủ thời gian bắt đầu và kết thúc.']);
            }

            // Kiểm tra thời gian hợp lý
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);
            $duration = $endTime->diffInHours($startTime);

            if ($duration < 1) {
                return back()->withErrors(['error' => 'Thời gian làm việc phải ít nhất 1 giờ.']);
            }

            if ($duration > 12) {
                return back()->withErrors(['error' => 'Thời gian làm việc không được quá 12 giờ.']);
            }
        }

        // Kiểm tra xem đã có yêu cầu cho ngày này chưa
        $existingRequest = LeaveRequest::where('user_id', Auth::id())
            ->where('request_date', $request->request_date)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return back()->withErrors(['request_date' => 'Bạn đã có yêu cầu cho ngày này.']);
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'request_date' => $request->request_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employees.leave.history')
            ->with('success', 'Yêu cầu đã được gửi thành công!');
    }

    // Hiển thị lịch sử yêu cầu cho nhân viên
    public function history()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())
            ->orderBy('request_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employees.leave-history', compact('leaveRequests'));
    }

    // Manager xem danh sách yêu cầu của nhân viên
    public function index()
    {
        $managerId = Auth::user()->id;
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->pluck('id');

        $leaveRequests = LeaveRequest::with('user')
            ->whereIn('user_id', $employeeIds)
            ->orderBy('request_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leave_management', compact('leaveRequests'));
    }

    // Manager cập nhật trạng thái yêu cầu
    public function updateStatus(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // Kiểm tra quyền: chỉ manager của nhân viên đó mới có thể phê duyệt
        $managerId = Auth::user()->id;
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->pluck('id');
            
        if (!in_array($leaveRequest->user_id, $employeeIds->toArray())) {
            abort(403, 'Bạn không có quyền phê duyệt yêu cầu này.');
        }
        
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'manager_note' => 'nullable|string|max:500',
        ]);

        // Lưu trạng thái cũ để kiểm tra
        $oldStatus = $leaveRequest->status;

        $updateData = [
            'status' => $request->status,
            'manager_notes' => $request->manager_note,
            'approver_id' => Auth::id(),
        ];

        // Cập nhật thời gian tương ứng với trạng thái
        if ($request->status === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['rejected_at'] = null;
        } else {
            $updateData['rejected_at'] = now();
            $updateData['approved_at'] = null;
        }

        $leaveRequest->update($updateData);

        // Xử lý attendance khi phê duyệt/từ chối
        if ($request->status === 'approved' && $oldStatus !== 'approved') {
            // Tạo hoặc cập nhật attendance khi phê duyệt
            $this->createOrUpdateAttendance($leaveRequest);
        } elseif ($request->status === 'rejected' && $oldStatus === 'approved') {
            // Khôi phục attendance về trạng thái ban đầu khi từ chối
            $this->revertAttendance($leaveRequest);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Tạo hoặc cập nhật attendance record khi phê duyệt yêu cầu
     */
    private function createOrUpdateAttendance(LeaveRequest $leaveRequest)
    {
        $user = $leaveRequest->user;
        $requestDate = $leaveRequest->request_date;

        // Tìm attendance record hiện tại cho ngày này
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $requestDate)
            ->whereNull('overtime_id') // Chỉ xử lý attendance thường, không phải overtime
            ->first();

        // Tạo hoặc lấy shift phù hợp dựa trên thời gian yêu cầu
        $shiftId = $this->getOrCreateShiftForLeaveRequest($leaveRequest);

        if (!$attendance) {
            // Tạo attendance record mới nếu chưa có
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $requestDate,
                'shift_id' => $shiftId,
                'check_in_time' => null,
                'check_out_time' => null,
                'status' => $this->mapLeaveTypeToStatus($leaveRequest->type),
                'face_image' => null,
            ]);
        } else {
            // Nếu đã có attendance record
            if ($attendance->check_in_time) {
                // Nếu nhân viên đã chấm công, chỉ cập nhật trạng thái nếu cần thiết
                if ($leaveRequest->type === 'late' && $attendance->status === 'present') {
                    $attendance->update([
                        'status' => 'late',
                        'shift_id' => $shiftId, // Cập nhật shift_id phù hợp
                    ]);
                } elseif ($leaveRequest->type === 'early_leave' && $attendance->status === 'present') {
                    $attendance->update([
                        'status' => 'early_leave',
                        'shift_id' => $shiftId, // Cập nhật shift_id phù hợp
                    ]);
                }
            } else {
                // Nếu chưa chấm công, cập nhật trạng thái và shift_id theo yêu cầu
                $attendance->update([
                    'status' => $this->mapLeaveTypeToStatus($leaveRequest->type),
                    'shift_id' => $shiftId,
                ]);
            }
        }
    }

    /**
     * Tạo hoặc lấy shift phù hợp dựa trên thời gian yêu cầu
     */
    private function getOrCreateShiftForLeaveRequest(LeaveRequest $leaveRequest)
    {
        $user = $leaveRequest->user;
        $managerId = $user->manager;

        // Nếu yêu cầu nghỉ phép cả ngày, sử dụng shift mặc định
        if ($leaveRequest->type === 'leave') {
            $defaultShift = Shift::where('user_id', $managerId)->first();
            return $defaultShift ? $defaultShift->id : 1;
        }

        // Nếu yêu cầu đi muộn hoặc về sớm, tạo shift động
        if ($leaveRequest->type === 'late' || $leaveRequest->type === 'early_leave') {
            $startTime = $leaveRequest->start_time;
            $endTime = $leaveRequest->end_time;

            if ($startTime && $endTime) {
                // Tìm shift có thời gian tương tự đã tồn tại
                $existingShift = Shift::where('user_id', $managerId)
                    ->where('start_time', $startTime)
                    ->where('end_time', $endTime)
                    ->first();

                if ($existingShift) {
                    return $existingShift->id;
                }

                // Tạo shift mới với thời gian từ yêu cầu
                $shiftName = $this->generateShiftName($leaveRequest->type, $startTime, $endTime);
                
                $newShift = Shift::create([
                    'name' => $shiftName,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'user_id' => $managerId,
                ]);

                return $newShift->id;
            }
        }

        // Fallback về shift mặc định
        $defaultShift = Shift::where('user_id', $managerId)->first();
        return $defaultShift ? $defaultShift->id : 1;
    }

    /**
     * Tạo tên shift dựa trên loại yêu cầu và thời gian
     */
    private function generateShiftName($leaveType, $startTime, $endTime)
    {
        $startHour = \Carbon\Carbon::parse($startTime)->format('H:i');
        $endHour = \Carbon\Carbon::parse($endTime)->format('H:i');
        
        return match($leaveType) {
            'late' => "Ca đi muộn ({$startHour}-{$endHour})",
            'early_leave' => "Ca về sớm ({$startHour}-{$endHour})",
            default => "Ca tùy chỉnh ({$startHour}-{$endHour})"
        };
    }

    /**
     * Khôi phục attendance về trạng thái ban đầu khi từ chối yêu cầu
     */
    private function revertAttendance(LeaveRequest $leaveRequest)
    {
        $user = $leaveRequest->user;
        $requestDate = $leaveRequest->request_date;

        // Tìm attendance record cho ngày này
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $requestDate)
            ->whereNull('overtime_id')
            ->first();

        if ($attendance) {
            // Lưu shift_id hiện tại để kiểm tra xem có cần xóa không
            $currentShiftId = $attendance->shift_id;
            
            // Nếu đã có check_in_time (đã chấm công), giữ nguyên trạng thái present
            if ($attendance->check_in_time) {
                $attendance->update([
                    'status' => 'present',
                ]);
            } else {
                // Nếu chưa chấm công, khôi phục về trạng thái absent
                $attendance->update([
                    'status' => 'absent',
                ]);
            }

            // Kiểm tra xem có cần xóa shift đã tạo không
            $this->cleanupUnusedShift($currentShiftId, $leaveRequest);
        }
    }

    /**
     * Xóa shift không sử dụng nếu được tạo tự động cho yêu cầu này
     */
    private function cleanupUnusedShift($shiftId, LeaveRequest $leaveRequest)
    {
        // Chỉ xóa shift nếu nó được tạo cho yêu cầu đi muộn hoặc về sớm
        if ($leaveRequest->type === 'late' || $leaveRequest->type === 'early_leave') {
            $shift = Shift::find($shiftId);
            
            if ($shift) {
                // Kiểm tra xem shift này có được tạo tự động không (dựa vào tên)
                $expectedName = $this->generateShiftName($leaveRequest->type, $leaveRequest->start_time, $leaveRequest->end_time);
                
                if ($shift->name === $expectedName) {
                    // Kiểm tra xem có attendance nào khác đang sử dụng shift này không
                    $otherAttendance = Attendance::where('shift_id', $shiftId)
                        ->where('id', '!=', $leaveRequest->id)
                        ->first();
                    
                    if (!$otherAttendance) {
                        // Nếu không có attendance nào khác sử dụng, xóa shift
                        $shift->delete();
                    }
                }
            }
        }
    }

    /**
     * Map loại yêu cầu nghỉ phép sang trạng thái attendance
     */
    private function mapLeaveTypeToStatus($leaveType)
    {
        return match($leaveType) {
            'late' => 'late',
            'leave' => 'leave',
            'early_leave' => 'early_leave',
            default => 'absent'
        };
    }

    // Xóa yêu cầu (chỉ cho phép xóa yêu cầu pending của chính mình)
    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        if ($leaveRequest->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa yêu cầu này.');
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể xóa yêu cầu đang chờ duyệt.']);
        }

        $leaveRequest->delete();

        return redirect()->route('employees.leave.history')
            ->with('success', 'Yêu cầu đã được xóa thành công!');
    }
}
