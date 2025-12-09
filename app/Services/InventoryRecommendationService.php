<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InventoryRecommendationService
{
    public function productsToReplenish(string $start, string $end): Collection
    {
        $startDate = $start;
        $endDate   = $end;

        // Rango del año anterior
        $startLastYear = date('Y-m-d', strtotime("$start -1 year"));
        $endLastYear   = date('Y-m-d', strtotime("$end -1 year"));

        // Consumo del rango actual
        $consumoActual = StockMovement::select(
                'product_id',
                DB::raw("SUM(quantity) as total_consumed")
            )
            ->where('type', 'sale')
            ->whereBetween('movement_date', [$startDate, $endDate])
            ->groupBy('product_id')
            ->pluck('total_consumed', 'product_id');

        // Consumo del rango del año anterior
        $consumoAnterior = StockMovement::select(
                'product_id',
                DB::raw("SUM(quantity) as total_consumed")
            )
            ->where('type', 'sale')
            ->whereBetween('movement_date', [$startLastYear, $endLastYear])
            ->groupBy('product_id')
            ->pluck('total_consumed', 'product_id');

        // Stock actual
        $stockActual = StockMovement::select(
                'product_id',
                DB::raw("
                SUM(CASE 
                    WHEN type = 'in' THEN quantity 
                    ELSE -quantity 
                END) as stock
            "))
            ->groupBy('product_id')
            ->pluck('stock', 'product_id');

        // Resultado final
        $result = collect();

        Product::all()->each(function ($product) use (
            $consumoActual,
            $consumoAnterior,
            $stockActual,
            $result
        ) {
            $consumoAñoActual = $consumoActual[$product->id] ?? 0;
            $consumoAñoAnterior = $consumoAnterior[$product->id] ?? 0;
            $stock = $stockActual[$product->id] ?? 0;

            // Proyección basada en año anterior
            $demandaEstimada = $consumoAñoAnterior;

            $faltante = $demandaEstimada - $stock;

            $result->push([
                'product_id' => $product->id,
                'product' => $product->name,
                'consumo_actual' => $consumoAñoActual,
                'consumo_anterior' => $consumoAñoAnterior,
                'stock_actual' => $stock,
                'demanda_estimada' => $demandaEstimada,
                'faltante' => $faltante,
                'necesita_compra' => $faltante > 0
            ]);
        });

        return $result->filter(fn ($item) => $item['necesita_compra']);
    }
}