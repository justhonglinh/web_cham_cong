<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function show()
    {
        if(Auth::user()->role == 'manager'){
            $managerId = Auth::user()->id;

            $overtimeShifts = OvertimeShift::with(['overtimeRequests' => function ($query) {
                $query->with('user')->orderBy('created_at', 'asc');
            }])
            ->where('date', '>=', now()->toDateString())
            ->where('user_id', $managerId)
            ->paginate(10);

            return view('overtime_management', compact('overtimeShifts'));

        }else{
            $managerId = Auth::user()->manager;

            $overtimeShifts = OvertimeShift::with('overtimeRequests')
                ->whereColumn('current_registrations', '<', 'max_registrations')
                ->where('user_id', $managerId)
                ->where('date', '>=', now()->toDateString())
                ->get();
            
            return view('employees.overtime', compact('overtimeShifts'));
        }
    }

    // Nhân viên đăng ký làm thêm giờ
    public function register(Request $request, $shiftId)
    {
        $overtimeShift = OvertimeShift::findOrFail($shiftId);
        $userId = Auth::id();

        // Kiểm tra xem ca làm thêm có còn slot không
        if ($overtimeShift->isFull) {
            return back()->withErrors(['error' => 'Ca làm thêm này đã đầy.']);
        }

        // Kiểm tra xem nhân viên đã đăng ký ca này chưa
        $existingRequest = OvertimeRequest::where('user_id', $userId)
            ->where('overtime_shift_id', $shiftId)
            ->first();

        if ($existingRequest) {
            return back()->withErrors(['error' => 'Bạn đã đăng ký ca làm thêm này rồi.']);
        }

        // Tạo yêu cầu đăng ký
        OvertimeRequest::create([
            'user_id' => $userId,
            'overtime_shift_id' => $shiftId,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đăng ký làm thêm giờ thành công! Yêu cầu đang chờ phê duyệt.');
    }

    // Nhân viên hủy đăng ký làm thêm giờ
    public function unregister(Request $request, $shiftId)
    {
        $userId = Auth::id();

        // Tìm yêu cầu đăng ký
        $overtimeRequest = OvertimeRequest::where('user_id', $userId)
            ->where('overtime_shift_id', $shiftId)
            ->first();

        if (!$overtimeRequest) {
            return back()->withErrors(['error' => 'Không tìm thấy yêu cầu đăng ký.']);
        }

        // Chỉ cho phép hủy nếu chưa được phê duyệt
        if ($overtimeRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Không thể hủy yêu cầu đã được phê duyệt.']);
        }

        $overtimeRequest->delete();

        return back()->with('success', 'Hủy đăng ký làm thêm giờ thành công!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'max_registrations' => 'required|integer|min:1',
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        OvertimeShift::create($request->all());

        return redirect()->route('overtime.index')->with('success', 'Thêm làm thêm giờ thành công!');
    }

    // Cập nhật overtime (từ modal Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'max_registrations' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $overtime = OvertimeShift::findOrFail($id);
        $overtime->update($request->all());

        return redirect()->route('overtime.index')->with('success', 'Cập nhật làm thêm giờ thành công!');
    }

    // Xóa overtime
    public function destroy($id)
    {
        $overtime = OvertimeShift::findOrFail($id);
        $overtime->delete();

        return redirect()->route('overtime.index')->with('success', 'Xóa làm thêm giờ thành công!');
    }
}
