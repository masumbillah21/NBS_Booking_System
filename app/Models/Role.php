<?php

namespace App\Models;

use App\Trait\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, DateTrait;

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['pivot'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
