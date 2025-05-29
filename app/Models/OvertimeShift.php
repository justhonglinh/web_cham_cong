<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OvertimeShift extends Model
{
    protected $table = 'overtime_shifts';

    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'description',
        'current_registrations', // nếu có cột này trong DB
        'max_registrations',     // nếu có cột này trong DB
    ];

    public function overtimeRequests(): HasMany
    {
        return $this->hasMany(OvertimeRequest::class, 'overtime_shift_id', 'id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}