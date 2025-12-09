<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['sale', 'purchase', 'adjustment'];
        $type = $this->faker->randomElement($types);

        return [
            'product_id' => Product::inRandomOrder()->first()->id,

            // cliente solo para ventas
            'customer_id' => $type === 'sale' 
                ? Customer::inRandomOrder()->first()->id 
                : null,

            // proveedor solo para compras
            'vendor_id' => $type === 'purchase' 
                ? Vendor::inRandomOrder()->first()->id 
                : null,

            'movement_date' => $this->faker->dateTimeBetween('2024-01-01', '2024-12-31'),

            'type' => $type,
            'quantity' => $this->faker->numberBetween(1, 20),
        ];
    }
}
