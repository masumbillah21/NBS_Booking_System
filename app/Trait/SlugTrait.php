<?php 

namespace App\Trait;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait SlugTrait
{
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }
}