<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OvertimeRequest extends Model
{
    protected $table = 'overtime_requests';

    protected $fillable = [
        'user_id',
        'overtime_shift_id',
        'status', // chỉ cần status, không cần reason nếu nhân viên chỉ xác nhận đồng ý/từ chối
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function overtimeShift(): BelongsTo
    {
        return $this->belongsTo(OvertimeShift::class, 'overtime_shift_id', 'id');
    }
}