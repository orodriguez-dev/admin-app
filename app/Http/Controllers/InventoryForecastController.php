<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Services\InventoryService; 
use App\Services\InventoryForecastingService;

class InventoryForecastController extends Controller
{
    protected $inventoryService;
    protected $forecastingService;
    
    // Inyección de dependencias en el constructor
    public function __construct(InventoryService $inventoryService, InventoryForecastingService $forecastingService)
    {
        $this->inventoryService = $inventoryService;
        $this->forecastingService = $forecastingService;
    }

    // Ruta GET: Carga inicial de la página
    public function index()
    {
        $products = Product::all(['id', 'name']);
        
        // Renderiza el componente en la ruta resources/js/Pages/Inventory/InventoryForecast.vue
        return Inertia::render('Inventory/InventoryForecast', [ 
            'products' => $products,
            'initialResults' => null,
        ]);
    }

    // Ruta POST: Procesa el cálculo
    public function calculate(Request $request)
    {
        // 1. Validación (Usamos la validación estándar de Laravel)
        $validatedData = $request->validate([
            'producto_id' => 'required|exists:products,id', // Usamos 'products' como nombre de tabla
            'dias_a_predecir' => 'required|integer|min:1|max:365',
        ]);

        $productId = $validatedData['producto_id'];
        $daysToForecast = $validatedData['dias_a_predecir'];

        try {
            // 2. Ejecutar el CORE y obtener datos
            $producto = Product::findOrFail($productId);
            
            // Stock actual (Llama al InventoryService)
            $currentStock = $this->inventoryService->calculateStock($productId); 

            // Demanda proyectada (Llama al InventoryForecastingService)
            $predictedDemand = $this->forecastingService->calculatePredictedDemand(
                $productId, 
                $daysToForecast
            );

            // 3. Calcular la necesidad de compra
            $unitsToBuy = max(0, $predictedDemand - $currentStock); 

            // 4. Crear el arreglo de resultados
            $results = [
                'producto' => [
                    'id' => $producto->id,
                    'name' => $producto->name,
                ],
                'daysToForecast' => $daysToForecast,
                'currentStock' => $currentStock,
                'predictedDemand' => $predictedDemand,
                'unitsToBuy' => $unitsToBuy,
            ];

            // 5. Redirigir/Renderizar de vuelta con los resultados
            return Inertia::render('Inventory/InventoryForecast', [
                'products' => Product::all(['id', 'name']), // Recargar la lista de productos
                'initialResults' => $results,
            ]);

        } catch (\Exception $e) {
            // Manejo de errores (ej. si el Core lanza una excepción por falta de datos)
            return redirect()->route('forecast.index')->with('error', 'Error en el cálculo: ' . $e->getMessage());
        }
    }
}