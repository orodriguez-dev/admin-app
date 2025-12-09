<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'vendor_id',
        'customer_id',
        'type',
        'quantity',
        'unit_cost',
        'total_cost',
        'movement_date',
    ];
}
