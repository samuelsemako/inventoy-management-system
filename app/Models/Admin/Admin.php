<?php

namespace App\Models\Admin;

use App\Models\Setup\SetupTitle;
use App\Models\Setup\SetupGender;
use App\Models\Setup\SetupStatus;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
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

    public function status()
    {
        return $this->belongsTo(SetupStatus::class, 'status_id', 'status_id');
    }

    public function gender()
    {
        return $this->belongsTo(SetupGender::class, 'gender_id', 'gender_id');
    }

    public function title()
    {
        return $this->belongsTo(SetupTitle::class, 'title_id', 'title_id');
    }
}
