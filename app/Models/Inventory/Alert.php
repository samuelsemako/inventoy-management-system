<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $primaryKey = 'alert_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'alert_id',
        'product_id',
        'alert_type',
        'message',
        'status_id',
    ];

    const TYPE_LOW_STOCK = 'low_stock';
    const TYPE_OUT_OF_STOCK = 'out_of_stock';
    const STATUS_UNREAD = 1;
    const STATUS_READ = 2;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
