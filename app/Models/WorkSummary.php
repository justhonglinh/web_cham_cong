<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSummary extends Model
{
    protected $table = 'work_summary';

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_work_hours',
        'total_overtime_hours',
        'total_leave_days',
        'total_absent_days',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
