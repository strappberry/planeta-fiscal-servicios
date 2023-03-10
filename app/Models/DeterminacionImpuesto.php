<?php

namespace App\Models;

use App\Models\Concerns\PerteneceAUnCliente;
use App\Models\QueryBuilders\DeterminacionImpuestosQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon $mes_trabajo
 * @property float $ingresos_acumulados
 * @property float $deducciones_acumuladas
 * @property float $pp_pagados
 * @property float $isr_actividad
 * @property array $determinacion
 * @property array $deducciones
 * @property array $calculos_iva_isr
 * @property array $impuestos_federales
 * @property Cliente $cliente
 */
class DeterminacionImpuesto extends Model
{
    use HasFactory;
    use PerteneceAUnCliente;

    protected $fillable = [
        'mes_trabajo',
        'ingresos_acumulados',
        'deducciones_acumuladas',
        'pp_pagados',
        'isr_actividad',
        'determinacion',
        'deducciones',
        'calculos_iva_isr',
        'impuestos_federales',
        'cliente_id',
    ];

    protected $casts = [
        'fecha_trabajo'          => 'date',
        'ingresos_acumulados'    => 'float',
        'deducciones_acumuladas' => 'float',
        'pp_pagados'             => 'float',
        'isr_actividad'          => 'float',
        'determinacion'          => 'array',
        'deducciones'            => 'array',
        'calculos_iva_isr'       => 'array',
        'impuestos_federales'    => 'array',
    ];

    public function newEloquentBuilder($query)
    {
        return new DeterminacionImpuestosQueryBuilder($query);
    }
}
