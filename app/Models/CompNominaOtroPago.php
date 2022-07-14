<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompNominaOtroPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_otro_pago',
        'clave',
        'concepto',
        'importe',
        'comp_nomina_id',
    ];

    protected $casts = [
        'importe' => 'float',
    ];

    /**
     * Nomina a la que pertenece la percepcion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function complemento()
    {
        return $this->belongsTo(CompNomina::class);
    }
}
