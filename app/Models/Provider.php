<?php

namespace App\Models;

use App\Trait\DateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory,DateTrait;
    protected $fillable = [
        'user_id',
        'company_name',
        'category_id',
        'description',
        'logo',
        'address',
        'phone_number',
        'email',
        'status',
        'slug'
    ];

    function Users(){
        return $this->hasMany(User::class);
    }

    function category(){
        return $this->belongsTo(Category::class);
    }
}
