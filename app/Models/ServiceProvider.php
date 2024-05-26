<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','company_name','description','logo','address','phone_number','email','status'];
}
