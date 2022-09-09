<?php

namespace Database\Factories;

use App\Models\BalanzaComprobacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BalanzaComprobacion>
 */
class BalanzaComprobacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'numero_cuenta' => $this->faker->string(10),
            'descripcion'   => $this->faker->sentence,
            'tipo'          => $this->faker->randomElement([
                BalanzaComprobacion::TIPO_AUXILIAR
            ]),
        ];
    }
}
