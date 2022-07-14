<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompNomina extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'tipo_nomina',
        'fecha_pago',
        'fecha_inicial',
        'fecha_final',
        'num_dias_pagados',
        'total_percepciones',
        'total_deducciones',
        'total_otros_pagos',
        'percepciones_total_sueldos',
        'percepciones_total_gravado',
        'percepciones_total_exento',
        'deducciones_total_otras_deducciones',
        'deducciones_total_imp_retenidos',
        'factura_id',
    ];

    protected $casts = [
        'fecha_pago'                          => 'date',
        'fecha_inicial'                       => 'date',
        'fecha_final'                         => 'date',
        'num_dias_pagados'                    => 'float',
        'total_percepciones'                  => 'float',
        'total_deducciones'                   => 'float',
        'total_otros_pagos'                   => 'float',
        'percepciones_total_sueldos'          => 'float',
        'percepciones_total_gravado'          => 'float',
        'percepciones_total_exento'           => 'float',
        'deducciones_total_otras_deducciones' => 'float',
        'deducciones_total_imp_retenidos'     => 'float',
    ];

    /**
     * Factura a la que pertenece la nomina.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    /**
     * Percepciones de la nomina.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function percepciones()
    {
        return $this->hasMany(CompNominaPercepcion::class);
    }

    /**
     * Deducciones de la nomina.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deducciones()
    {
        return $this->hasMany(CompNominaDeduccion::class);
    }

    /**
     * Otros pagos de la nomina.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function otrosPagos()
    {
        return $this->hasMany(CompNominaOtroPago::class);
    }
}
