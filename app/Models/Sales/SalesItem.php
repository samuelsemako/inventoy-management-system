<?php

namespace App\Models\Sales;

use App\Models\Inventory\Product;
use App\Models\Inventory\Supplier;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    protected $primaryKey = 'sales_item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sales_item_id',
        'sales_id',
        'product_id',
        'unit_price',
        'sub_total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
