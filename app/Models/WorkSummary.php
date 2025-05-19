<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSummary extends Model
{
    protected $table = 'work_summary';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
