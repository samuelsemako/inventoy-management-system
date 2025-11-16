<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class SetupGender extends Model
{
    protected $primaryKey = 'gender_id';
    protected $fillable = ['gender_name'];

    public function gender()
    {
        return $this->hasMany(SetupStatus::class, 'gender_id', 'gender_id');
    }
}
