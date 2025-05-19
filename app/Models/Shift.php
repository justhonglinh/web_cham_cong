<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $table = 'shifts';

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}

