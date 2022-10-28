<?php

namespace App\Models;

use App\Models\EloquentCollections\FacturaClienteCollection;
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
        'poliza_valida',
        'concepto_deduccion_personal_id',
        'concepto_sat_id',
        'deducible',
    ];

    protected $casts = [
        'considerado'   => 'boolean',
        'poliza_valida' => 'boolean',
        'deducible'     => 'boolean',
    ];

    protected $dates = [
        'fecha_emision',
        'fecha_pago',
    ];

    public function newEloquentBuilder($query)
    {
        return new FacturaClienteQueryBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new FacturaClienteCollection($models);
    }

    public function conceptoDeduccionPersonal()
    {
        return $this->belongsTo(ConceptoDeduccionPersonal::class);
    }

    public function conceptoSat()
    {
        return $this->belongsTo(ConceptoSat::class);
    }

    public function cuentaPoliza()
    {
        return $this->belongsTo(NumeroCuenta::class, 'cuenta_poliza');
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

}
