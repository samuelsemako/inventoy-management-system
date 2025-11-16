<?php

namespace App\Models\Inventory;
use App\Models\Setup\SetupStatus;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id'; 
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'supplier_id',
        'supplier_name',
        'email_address',
        'phone_number',
        'supplier_address',
        'status_id',
        'created_by',
        'updated_by',
    ]; 

    public function supplier()
    {
        return $this->hasMany(Supplier::class, 'supplier_id', 'supplier');
    }

    public function status()
    {
        return $this->belongsTo(SetupStatus::class, 'status_id', 'status_id');
    }

}
