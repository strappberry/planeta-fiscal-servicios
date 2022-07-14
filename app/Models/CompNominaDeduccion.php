<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompNominaDeduccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_deduccion',
        'clave',
        'concepto',
        'importe',
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
