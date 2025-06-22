<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'request_date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'manager_notes',
        'approved_at',
        'rejected_at',
        'approver_id',
    ];

    protected $casts = [
        'request_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Helper methods
    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'late' => 'Đi muộn',
            'leave' => 'Nghỉ phép',
            'early_leave' => 'Về sớm',
            default => 'Không xác định'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã phê duyệt',
            'rejected' => 'Đã từ chối',
            default => 'Không xác định'
        };
    }
}
