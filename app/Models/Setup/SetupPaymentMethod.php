<?php

namespace App\Models\setup;

use Illuminate\Database\Eloquent\Model;

class SetupPaymentMethod extends Model
{
    protected $primaryKey = 'payment_method_id';
    protected $fillable = ['payment_method_name'];
}
