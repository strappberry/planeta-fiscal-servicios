<?php

namespace Database\Factories;

use App\Models\BalanzaComprobacion;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BalanzaComprobacionCliente>
 */
class BalanzaComprobacionClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'fecha'                   => $this->faker->date(),
            'saldo_inicial'           => $this->faker->randomFloat(2, 0, 100000),
            'saldo_final'             => $this->faker->randomFloat(2, 0, 100000),
            'balanza_comprobacion_id' => BalanzaComprobacion::factory(),
            'cliente_id'              => Cliente::factory(),
        ];
    }
}
