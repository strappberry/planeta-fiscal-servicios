<?php

namespace App\Models;

use App\Models\QueryBuilders\FacturaClienteQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaCliente extends Model
{
    use HasFactory;

    const TIPO_VENTA = 'venta';
    const TIPO_GASTO = 'gasto';

    protected $fillable = [
        'fecha_emision',
        'considerado',
        'cliente_id',
        'factura_id',
        'numero_cuenta_id',
        'fecha_pago',
        'cuenta_poliza',
        'tipo_factura',
    ];

    protected $casts = [
        'considerado' => 'boolean',
    ];

    protected $dates = [
        'fecha_emision',
        'fecha_pago',
    ];

    public function newEloquentBuilder($query)
    {
        return new FacturaClienteQueryBuilder($query);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function numeroCuenta()
    {
        return $this->belongsTo(NumeroCuenta::class);
    }

    public function numerosCuentas()
    {
        return $this->belongsToMany(NumeroCuenta::class)
            ->withPivot([
                'monto',
            ])
            ->as('relacion_numero_cuenta')
            ->using(FacturaClienteNumeroCuenta::class);
    }

    public function cuentaPoliza()
    {
        return $this->belongsTo(NumeroCuenta::class, 'cuenta_poliza');
    }
}
