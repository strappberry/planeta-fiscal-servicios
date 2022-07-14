<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompNominaPercepcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_percepcion',
        'clave',
        'concepto',
        'importe_gravado',
        'importe_exento',
        'comp_nomina_id',
    ];

    protected $casts = [
        'importe_gravado' => 'float',
        'importe_exento'  => 'float',
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
