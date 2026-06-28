<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLeaveRequestController extends Controller
{
    public function management(Request $request)
    {
        $managerId = Auth::user()->id;
        $requests = LeaveRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with('user')->orderByDesc('created_at')->paginate(20);
        return response()->json($requests);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->status = $request->status;
        $leaveRequest->save();
        return response()->json(['message' => 'Cập nhật thành công', 'request' => $leaveRequest]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string',
        ]);

        LeaveRequest::create([
            'user_id'    => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        return response()->json(['message' => 'Gửi yêu cầu thành công'], 201);
    }

    public function history(Request $request)
    {
        $requests = LeaveRequest::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);
        return response()->json($requests);
    }

    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();
        $leaveRequest->delete();
        return response()->json(['message' => 'Hủy yêu cầu thành công']);
    }
}
