<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OvertimeShift extends Model
{
    protected $table = 'overtime_shifts';

    protected $fillable = [
        'user_id',
        'name',
        'date',
        'start_time',
        'end_time',
        'description',
        'max_registrations',
        'current_registrations',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function overtimeRequests()
    {
        return $this->hasMany(OvertimeRequest::class, 'overtime_shift_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'overtime_id');
    }

    // Helper methods
    public function getAvailableSlotsAttribute()
    {
        return max(0, $this->max_registrations - $this->current_registrations);
    }

    public function isFullAttribute()
    {
        return $this->current_registrations >= $this->max_registrations;
    }
}
