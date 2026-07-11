<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'shift_id',    // nếu bạn muốn mass assign shift_id
        'overtime_id', // nếu bạn muốn mass assign overtime_shift_id
        'date',        // thêm trường ngày
        'check_in_time',
        'check_out_time',
        'status',      // thêm trạng thái điểm danh
        'face_image',  // thêm trường ảnh chấm công
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function overtimeShift()
    {
        return $this->belongsTo(OvertimeShift::class, 'overtime_id');
    }
}
