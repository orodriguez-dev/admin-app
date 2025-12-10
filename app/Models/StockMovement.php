<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StockMovementType;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements'; // Nombre de tu tabla

    protected $fillable = [
        'product_id',
        'vendor_id',
        'customer_id',
        'type', // Usará el valor string del Enum
        'quantity',
        'unit_cost',
        'total_cost',
        'movement_date',
    ];

    /**
     * Casting para usar el Enum y la fecha
     * El 'type' se mapea automáticamente a tu Enum.
     */
    protected $casts = [
        'type' => StockMovementType::class, 
        'movement_date' => 'date', 
    ];

    // Relaciones
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Asumiendo que tienes modelos Vendor y Customer...
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
