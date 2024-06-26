<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Trait\DateTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, DateTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'designation',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }
  
    public function appointmentClient(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'client_id');
    }

    public function appointmentStaff(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'staff_id');
    }

    public function hasPermission($permission)
    {
        return $this->roles->flatMap(function ($role) use ($permission) {
            return $role->permissions->pluck('permission');
        })->contains($permission);
    }
}
