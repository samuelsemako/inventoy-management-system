<?php

namespace App\Models\Inventory;

use App\Models\Setup\SetupStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['category_id', 'category_name', 'status_id', 'created_by', 'updated_by'];


    //category relation
    public function category()
    {
        return $this->hasMany($this::class, 'category_id', 'category_id');
    }
    
    //status relation
    public function status()
    {
        return $this->belongsTo(SetupStatus::class, 'status_id', 'status_id');
    }
}
