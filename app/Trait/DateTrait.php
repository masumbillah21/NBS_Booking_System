<?php 

namespace App\Trait;

use Carbon\Carbon;

trait DateTrait
{
    public function getCreatedAtAttribute($value)
    {

        return Carbon::parse($value)->diffForHumans();

    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function getClosingDateAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}