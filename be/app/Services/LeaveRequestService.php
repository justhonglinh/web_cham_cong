<?php

namespace App\Services;

use App\Contracts\Services\LeaveRequestServiceInterface;
use App\Models\LeaveRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveRequestService implements LeaveRequestServiceInterface
{
    public function getManagement(int $managerId): LengthAwarePaginator
    {
        return LeaveRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function updateStatus(int $id, string $status, int $approverId): LeaveRequest
    {
        $leave         = LeaveRequest::findOrFail($id);
        $leave->status = $status;

        if ($status === 'approved') {
            $leave->approved_at = now();
            $leave->approver_id = $approverId;
        } elseif ($status === 'rejected') {
            $leave->rejected_at = now();
            $leave->approver_id = $approverId;
        }

        $leave->save();

        return $leave;
    }

    public function create(array $data, int $userId): LeaveRequest
    {
        return LeaveRequest::create([
            'user_id'    => $userId,
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'reason'     => $data['reason'] ?? null,
            'status'     => 'pending',
        ]);
    }

    public function history(int $userId): LengthAwarePaginator
    {
        return LeaveRequest::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    public function delete(int $id, int $userId): void
    {
        LeaveRequest::where('id', $id)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->firstOrFail()
            ->delete();
    }
}
