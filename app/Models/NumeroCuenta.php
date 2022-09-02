<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroCuenta extends Model
{
    use HasFactory;

    const TIPO_GASTO = 'gasto';
    const TIPO_VENTA = 'venta';
    const TIPO_POLIZA = 'poliza';

    const SUBTIPO_FECHA_EMISION = 'fecha_emision';
    const SUBTIPO_FECHA_PAGO = 'fecha_pago';

    const COLUMNA_CALCULO_CARGO = 'cargo';
    const COLUMNA_CALCULO_ABONO = 'abono';

    protected $fillable = [
        'numero_cuenta',
        'descripcion',
        'tipo_cuenta',
        'subtipo',
        'automatico',
        'columna_calculo',
        'formula',
        'cliente_id'
    ];

    protected $casts = [
        'automatico' => 'boolean',
        'formula'    => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function facturasCliente()
    {
        return $this->hasMany(FacturaCliente::class);
    }
}
