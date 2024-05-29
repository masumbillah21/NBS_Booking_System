<?php

namespace App\Models;

use App\Trait\DateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceProvider extends Model
{
    use HasFactory,DateTrait;
    protected $fillable = ['user_id','company_name','description','logo','address','phone_number','email','status'];
    function Users(){
        return $this->belongsTo(User::class);
    }
}
