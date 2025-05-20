<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // Keep role as is
        'manager',  // Added id_manager
        'id_position', // Changed position to id_position to match DB
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // This cast will ensure it gets converted to a DateTime object
            'password' => 'hashed',            // This ensures the password is always hashed when set
        ];
    }

    /**
     * Get the manager of the user (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Nhân viên có nhiều bản tổng hợp công việc (work summary)
    public function workSummaries(): HasMany
    {
        return $this->hasMany(WorkSummary::class);
    }

    // Nhân viên có nhiều yêu cầu làm thêm
    public function overtimeRequests(): HasMany
    {
        return $this->hasMany(OvertimeRequest::class);
    }

    // Manager có nhiều nhân viên
    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'manager', 'name');
        // 'manager' là tên cột trong bảng users lưu tên manager
        // 'name' là khóa chính để đối chiếu
    }

    /**
     * Get the position of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
}
