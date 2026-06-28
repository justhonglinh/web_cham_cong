<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAttendanceController extends Controller
{
    public function management(Request $request)
    {
        $managerId = Auth::user()->id;
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $employeeIds = User::where('manager', $managerId)->pluck('id');
        $attendances = Attendance::with(['user', 'shift'])
            ->whereIn('user_id', $employeeIds)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderByDesc('date')
            ->paginate(20);
        return response()->json($attendances);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'check_in_time'  => 'nullable|date_format:H:i:s',
            'check_out_time' => 'nullable|date_format:H:i:s',
            'status'         => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->fill($request->only(['check_in_time', 'check_out_time', 'status']));
        $attendance->save();

        // Update work summary after saving attendance
        if (method_exists($attendance, 'updateWorkSummary')) {
            $attendance->updateWorkSummary();
        }

        return response()->json(['message' => 'Cập nhật thành công', 'attendance' => $attendance]);
    }

    public function today()
    {
        $user = Auth::user();
        $today = now()->toDateString();
        $attendance = Attendance::with(['shift', 'overtimeShift'])
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();
        return response()->json(['attendance' => $attendance]);
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $attendances = Attendance::with(['shift', 'overtimeShift'])
            ->where('user_id', $user->id)
            ->orderByDesc('date')
            ->paginate(15);
        return response()->json($attendances);
    }

    public function processAttendance(Request $request)
    {
        $request->validate(['action' => 'required|in:check_in,check_out']);
        $user = Auth::user();
        $today = now()->toDateString();
        $attendance = Attendance::firstOrNew(['user_id' => $user->id, 'date' => $today]);

        if ($request->action === 'check_in' && !$attendance->check_in_time) {
            $attendance->check_in_time = now()->format('H:i:s');
            $attendance->status = 'present';
        } elseif ($request->action === 'check_out' && $attendance->check_in_time && !$attendance->check_out_time) {
            $attendance->check_out_time = now()->format('H:i:s');
        }
        $attendance->save();
        return response()->json(['message' => 'Chấm công thành công', 'attendance' => $attendance]);
    }
}
