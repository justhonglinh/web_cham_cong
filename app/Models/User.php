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
        'id_manager',  // Added id_manager
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
    public function manager()
    {
        return $this->belongsTo(User::class, 'id_manager');
    }

    /**
     * Get the position of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function position()
//    {
//        return $this->belongsTo(Position::class, 'id_position');
//    }
}
