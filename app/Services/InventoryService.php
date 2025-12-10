<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Enums\StockMovementType; // Importamos tu Enum

class InventoryService
{
    /**
     * Calcula el stock actual de un producto.
     */
    public function calculateStock(int $productId): int
    {
        // Tipos que representan ENTRADA de stock
        $entryTypes = [
            StockMovementType::PURCHASE, 
            StockMovementType::ADJUSTMENT
        ];
        
        // Tipo que representa SALIDA de stock (Ventas)
        $exitType = StockMovementType::SALE;

        // Suma todas las ENTRADAS
        $entradas = StockMovement::where('product_id', $productId)
            ->whereIn('type', $entryTypes)
            ->sum('quantity');

        // Suma todas las SALIDAS
        $salidas = StockMovement::where('product_id', $productId)
            ->where('type', $exitType)
            ->sum('quantity');

        return $entradas - $salidas;
    }
}