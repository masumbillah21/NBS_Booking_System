<?php

namespace App\Models;

use App\Trait\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, DateTrait;

    protected $fillable = [
        'service_name',
        'description',
        'duration',
        'price',
        'provider_id',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

}
