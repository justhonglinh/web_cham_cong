<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ShiftController extends Controller
{
    public function show()
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->role !== 'manager') {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền truy cập trang này.']);
        }

        $userId = Auth::user()->id;
        $today = now()->toDateString();
        
        // Lấy tất cả shifts của user (chỉ của chính mình)
        $allShifts = Shift::where('user_id', $userId)->get();
        
        // Lọc ra những shift không có attendance với date < today
        $activeShifts = $allShifts->filter(function($shift) use ($today) {
            // Kiểm tra xem shift này có attendance nào với date < today không
            $hasOldAttendance = Attendance::where('shift_id', $shift->id)
                ->where('date', '<', $today)
                ->exists();
            
            // Chỉ hiển thị shift nếu KHÔNG có attendance cũ
            return !$hasOldAttendance;
        });
        
        // Tạo paginator cho collection đã lọc
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $shifts = new LengthAwarePaginator(
            $activeShifts->forPage($currentPage, $perPage),
            $activeShifts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('shifts_management', compact('shifts'));
    }

    /**
     * Hiển thị tất cả ca làm (bao gồm cả những ca đã ẩn)
     */
    public function showAll()
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->role !== 'manager') {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền truy cập trang này.']);
        }

        $userId = Auth::user()->id;
        $today = now()->toDateString();
        
        // Lấy tất cả shifts của user (chỉ của chính mình)
        $allShifts = Shift::where('user_id', $userId)->get();
        
        // Phân loại shifts
        $activeShifts = collect();
        $hiddenShifts = collect();
        
        foreach ($allShifts as $shift) {
            $hasOldAttendance = Attendance::where('shift_id', $shift->id)
                ->where('date', '<', $today)
                ->exists();
            
            if ($hasOldAttendance) {
                $hiddenShifts->push($shift);
            } else {
                $activeShifts->push($shift);
            }
        }
        
        // Tạo paginator cho tất cả shifts
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $shifts = new LengthAwarePaginator(
            $allShifts->forPage($currentPage, $perPage),
            $allShifts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $showAll = true;

        return view('shifts_management', compact('shifts', 'showAll', 'activeShifts', 'hiddenShifts'));
    }

    public function store(Request $request)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->role !== 'manager') {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền thực hiện hành động này.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'user_id' => 'required|exists:users,id',
        ]);

        // Đảm bảo manager chỉ có thể tạo ca làm cho chính mình
        if ($request->user_id != Auth::user()->id) {
            return redirect()->back()->withErrors(['error' => 'Bạn chỉ có thể tạo ca làm cho chính mình.']);
        }

        Shift::create($request->all());
        return redirect()->route('shifts.index')->with('success', 'Thêm ca làm thành công');
    }

    public function update(Request $request, $id)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->role !== 'manager') {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền thực hiện hành động này.']);
        }


        $shift = Shift::findOrFail($id);
        
        // Đảm bảo manager chỉ có thể sửa ca làm của chính mình
        if ($shift->user_id != Auth::user()->id) {
            return redirect()->back()->withErrors(['error' => 'Bạn chỉ có thể sửa ca làm của chính mình.']);
        }

        $shift->update($request->all());

        return redirect()->route('shifts.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->role !== 'manager') {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền thực hiện hành động này.']);
        }

        $shift = Shift::findOrFail($id);
        
        // Đảm bảo manager chỉ có thể xóa ca làm của chính mình
        if ($shift->user_id != Auth::user()->id) {
            return redirect()->back()->withErrors(['error' => 'Bạn chỉ có thể xóa ca làm của chính mình.']);
        }

        $today = now()->toDateString();
        
        // Kiểm tra xem ca làm có attendance hiện tại không
        $hasCurrentAttendance = Attendance::where('shift_id', $shift->id)
            ->where('date', '>=', $today)
            ->exists();
            
        // Kiểm tra xem ca làm có attendance cũ không
        $hasOldAttendance = Attendance::where('shift_id', $shift->id)
            ->where('date', '<', $today)
            ->exists();
        
        if ($hasCurrentAttendance) {
            return redirect()->back()->withErrors(['error' => 'Không thể xóa ca làm đang được sử dụng cho các ngày hiện tại hoặc tương lai.']);
        }
        
        // Nếu có attendance cũ, hiển thị cảnh báo nhưng vẫn cho phép xóa
        if ($hasOldAttendance) {
            // Có thể thêm logic backup hoặc log ở đây nếu cần
            $shift->delete();
            return redirect()->back()->with('warning', 'Ca làm cũ đã được xóa. Lưu ý: Việc này có thể ảnh hưởng đến dữ liệu lịch sử chấm công.');
        }
        
        // Xóa ca làm bình thường
        $shift->delete();
        return redirect()->back()->with('success', 'Xóa ca làm thành công.');
    }

    public function search(Request $request)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->role !== 'manager') {
            return response()->json(['error' => 'Bạn không có quyền thực hiện hành động này.'], 403);
        }

        $query = $request->input('query');
        $userId = Auth::user()->id;
        $today = now()->toDateString();

        // Lấy tất cả shifts của user (chỉ của chính mình)
        $allShifts = Shift::where('user_id', $userId)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('start_time', 'like', "%{$query}%")
                  ->orWhere('end_time', 'like', "%{$query}%");
            })
            ->get();
        
        // Lọc ra những shift không có attendance với date < today
        $activeShifts = $allShifts->filter(function($shift) use ($today) {
            $hasOldAttendance = Attendance::where('shift_id', $shift->id)
                ->where('date', '<', $today)
                ->exists();
            
            return !$hasOldAttendance;
        });

        return response()->json($activeShifts->values());
    }
}
