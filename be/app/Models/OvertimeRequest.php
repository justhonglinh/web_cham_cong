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
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function overtimeShift(): BelongsTo
    {
        return $this->belongsTo(OvertimeShift::class, 'overtime_shift_id');
    }
}
