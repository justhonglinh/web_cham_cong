<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OvertimeRequest extends Model
{
    protected $table = 'overtime_requests';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function overtimeShift(): BelongsTo
    {
        return $this->belongsTo(OvertimeShift::class);
    }
}
