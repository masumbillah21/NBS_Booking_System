<?php

namespace App\Models;

use App\Trait\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, DateTrait;

    protected $fillable = ['category_name', 'parent_id', 'description'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function services(){
        return $this->hasMany(Service::class);
    }

    public function providers(){
        return $this->hasMany(Provider::class);
    }
}
