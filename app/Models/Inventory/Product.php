<?php

namespace App\Models\Inventory;

use App\Models\Inventory\Category;
use App\Models\Inventory\Supplier;
use App\Models\Setup\SetupStatus;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'product_name',
        'category_id',
        'product_description',
        'selling_price',
        'cost_price',
        'stock_quantity',
        'supplier_id',
        'created_by',
        'updated_by',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
