<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\IdentificationType;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Provincias de Ecuador
        $provinces = [
            'Pichincha', 'Guayas', 'Azuay', 'Manabí', 'Loja',
            'El Oro', 'Tungurahua', 'Santo Domingo', 'Imbabura'
        ];

        // Ciudades comunes
        $cities = [
            'Quito', 'Guayaquil', 'Cuenca', 'Manta', 'Loja',
            'Machala', 'Ambato', 'Santo Domingo', 'Ibarra'
        ];

        return [
            'identification_type'   => $this->faker->randomElement([
                IdentificationType::ID_CARD->value,
                IdentificationType::RUC->value,
                IdentificationType::PASSPORT->value,
                IdentificationType::FINAL_CONSUMER->value,
            ]),
            'identification_number' => $this->faker->numerify('#############'),
            'name'                  => $this->faker->name(),
            'trade_name'            => $this->faker->company(),
            'email'                 => $this->faker->unique()->safeEmail(),
            'phone'                 => $this->faker->numerify('02########'),
            'mobile_phone'          => $this->faker->numerify('09########'),
            'country'               => 'Ecuador',
            'province'              => $this->faker->randomElement($provinces),
            'city'                  => $this->faker->randomElement($cities),
            'address'               => $this->faker->streetAddress(),
        ];
    }
}
