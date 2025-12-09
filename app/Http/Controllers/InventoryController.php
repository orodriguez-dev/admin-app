<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function replenish(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        $start = $request->start_date;
        $end   = $request->end_date;

        // Rango del año anterior
        $startPrev = date('Y-m-d', strtotime('-1 year', strtotime($start)));
        $endPrev   = date('Y-m-d', strtotime('-1 year', strtotime($end)));

        // Ventas del rango actual
        $currentSales = StockMovement::where('type', 'SALE')
            ->whereBetween('movement_date', [$start, $end])
            ->selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->pluck('total', 'product_id');

        // Ventas del año anterior
        $previousSales = StockMovement::where('type', 'SALE')
            ->whereBetween('movement_date', [$startPrev, $endPrev])
            ->selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->pluck('total', 'product_id');

        $products = Product::all()->map(function ($p) use ($currentSales, $previousSales) {

            $current = $currentSales[$p->id] ?? 0;
            $previous = $previousSales[$p->id] ?? 0;

            return [
                'id' => $p->id,
                'name' => $p->name,
                'current_sales' => $current,
                'previous_sales' => $previous,
                'difference' => $previous - $current,
                'needs_purchase' => ($previous > $current),
            ];
        });

        return inertia('Inventory/Replenish', [
            'products' => $products,
            'period' => [
                'current_start' => $start,
                'current_end' => $end,
                'previous_start' => $startPrev,
                'previous_end' => $endPrev,
            ]
        ]);
    }
}