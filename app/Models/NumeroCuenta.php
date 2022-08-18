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

    const SUBTIPO_POLIZA_VENTA = 'poliza_venta';
    const SUBTIPO_POLIZA_GASTO = 'poliza_gasto';

    protected $fillable = [
        'numero_cuenta',
        'descripcion',
        'tipo_cuenta',
        'subtipo',
        'poliza',
        'cargo',
    ];

    protected $casts = [
        'poliza' => 'boolean',
        'cargo'  => 'boolean',
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
