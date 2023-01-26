<?php

namespace App\Models;

use App\Models\Concerns\PerteneceAUnCliente;
use App\Models\QueryBuilders\DeterminacionImpuestosQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeterminacionImpuesto extends Model
{
    use HasFactory;
    use PerteneceAUnCliente;

    const COEFICIENTE_UTILIDAD = 'coeficiente_utilidad';

    protected $fillable = [
        'mes_trabajo',
        'ingresos_acumulados',
        'deducciones_acumuladas',
        'pp_pagados',
        'isr_actividad',
        'coeficiente_utilidad',
        'determinacion',
        'campos_editables',
        'cliente_id',
    ];

    protected $casts = [
        'fecha_trabajo'          => 'date',
        'ingresos_acumulados'    => 'float',
        'deducciones_acumuladas' => 'float',
        'pp_pagados'             => 'float',
        'isr_actividad'          => 'float',
        'coeficiente_utilidad'   => 'float',
        'determinacion'          => 'array',
        'campos_editables'       => 'array',
    ];

    public function newEloquentBuilder($query)
    {
        return new DeterminacionImpuestosQueryBuilder($query);
    }
}
