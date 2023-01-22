<?php

namespace App\Models;

use App\Models\Concerns\PerteneceAUnCliente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'campos_editables',
        'cliente_id',
    ];

    protected $casts = [
        'fecha_trabajo'          => 'date',
        'ingresos_acumulados'    => 'float',
        'deducciones_acumuladas' => 'float',
        'pp_pagados'             => 'float',
        'isr_actividad'          => 'float',
        'determinacion'          => 'array',
        'campos_editables'       => 'array',
    ];
}
