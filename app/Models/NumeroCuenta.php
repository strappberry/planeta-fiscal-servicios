<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroCuenta extends Model
{
    use HasFactory;

    const TIPO_GASTO = 'gasto';
    const TIPO_VENTA = 'venta';
    const TIPO_POLIZA_VENTAS = 'poliza_ventas';
    const TIPO_POLIZA_GASTOS = 'poliza_gastos';

    const SUBTIPO_FECHA_EMISION = 'fecha_emision';
    const SUBTIPO_FECHA_PAGO = 'fecha_pago';

    const COLUMNA_CALCULO_CARGO = 'cargo';
    const COLUMNA_CALCULO_ABONO = 'abono';

    protected $fillable = [
        'numero_cuenta',
        'descripcion',
        'tipo_cuenta',
        'subtipo',
        'tercer_tipo',
        'automatico',
        'columna_calculo',
        'formula',
        'cliente_id',
        'exclusiones',
        'residual_cargo_abono',
        'deducible',
    ];

    protected $casts = [
        'automatico'           => 'boolean',
        'formula'              => 'array',
        'exclusiones'          => 'array',
        'residual_cargo_abono' => 'boolean',
        'deducible'            => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function facturasManuales()
    {
        return $this->belongsToMany(FacturaCliente::class)
            ->withPivot([
                'monto',
            ])
            ->as('relacion_numero_cuenta')
            ->using(FacturaClienteNumeroCuenta::class);
    }

    public function facturasCliente()
    {
        return $this->hasMany(FacturaCliente::class);
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */
    public function scopeBuscarExclusion($query, $exclusion)
    {
        $exclusion = collect($exclusion);

        return $query
            ->where('numero_cuenta', $exclusion->get('numero_cuenta'))
            ->where('automatico', $exclusion->get('automatico'))
            ->where('tipo_cuenta', $exclusion->get('tipo_cuenta'))
            ->where('subtipo', $exclusion->get('subtipo'));
    }

    /*
     |--------------------------------------------------------------------------
     | Attributes
     |--------------------------------------------------------------------------
     */
}
