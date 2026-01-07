<?php

namespace App\Models\User;

use App\Models\Setup\SetupTitle;
use App\Models\Setup\SetupGender;
use App\Models\Setup\SetupStatus;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasApiTokens;
    protected $primaryKey = 'customer_id'; 
    public $incrementing = false; 
    protected $keyType = 'string';


    protected $fillable = [
        'customer_id',
        'title_id',
        'first_name', 
        'middle_name',
        'last_name',
        'gender_id',
        'email_address',
        'phone_number',
        'home_address',
        'status_id',
        'created_by',
        'updated_by',
        'last_login_at',
        'password'
    ]; 

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed',];
}
