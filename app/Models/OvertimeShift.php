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
    ];
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    public function overtimeRequests(): HasMany
    {
        return $this->hasMany(OvertimeRequest::class);
    }
}
