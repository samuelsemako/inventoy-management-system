<?php

namespace App\Models\Sales;

use App\Models\Admin\Admin;
use App\Models\User\Customer;
use App\Models\setup\SetupPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\setup\SetupPaymentMethod;

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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(SetupPaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    public function seller()
    {
        return $this->belongsTo(Admin::class, 'sold_by', 'admin_id');
    }
}
