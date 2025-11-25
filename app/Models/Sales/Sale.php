<?php

namespace App\Models\Sales;

use App\Models\setup\SetupPayment;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $primaryKey = 'sales_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sales_id',
        'customer_id',
        'total_amount',
        'sold_by',
        'payment_method_id',
    ];

    public function items()
    {
        return $this->hasMany(SalesItem::class, 'sales_id', 'sales_id');
    }
}
