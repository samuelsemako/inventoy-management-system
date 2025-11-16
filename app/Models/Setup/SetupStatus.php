<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class SetupStatus extends Model
{
    protected $primaryKey = 'status_id';
    protected $fillable = ['status_name'];
    
    public function status()
    {
        return $this->hasMany(SetupStatus::class, 'status_id', 'status_id');
    }
}
