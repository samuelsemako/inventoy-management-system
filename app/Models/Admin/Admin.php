<?php

namespace App\Models\Admin;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens,HasRoles;
    protected $primaryKey = 'admin_id'; 
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'admin_id',
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
