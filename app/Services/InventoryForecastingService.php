<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Enums\StockMovementType; // Importamos tu Enum
use Carbon\Carbon;

class InventoryForecastingService
{
    /**
     * Calcula la demanda proyectada para un período futuro basado en el mismo período del año anterior.
     */
    public function calculatePredictedDemand(int $productId, int $daysToForecast): int
    {
        // 1. Definir el Período de Historial (Año-sobre-Año)
        
        $endDateLastYear = Carbon::now()->subYear()->endOfDay();
        $startDateLastYear = (clone $endDateLastYear)->subDays($daysToForecast)->startOfDay();

        // 2. Obtener las VENTAS REALES del Período de Historial
        
        // Usamos StockMovement y filtramos por el tipo SALIDA (SALE)
        $historicalSales = StockMovement::where('product_id', $productId)
            ->where('type', StockMovementType::SALE) // Usando tu Enum
            ->whereBetween('movement_date', [$startDateLastYear, $endDateLastYear]) // Usamos movement_date (DATE)
            ->sum('quantity');
            
        // 3. Calcular el Promedio Diario de Demanda
        
        $actualDaysInPeriod = $startDateLastYear->diffInDays($endDateLastYear) + 1; 

        if ($actualDaysInPeriod === 0 || $historicalSales === 0) {
            return 0;
        }

        $dailyAverageDemand = $historicalSales / $actualDaysInPeriod;

        // 4. Proyectar la Demanda Futura
        
        $predictedDemand = $dailyAverageDemand * $daysToForecast;

        // Redondear al entero superior
        return (int) ceil($predictedDemand);
    }
}