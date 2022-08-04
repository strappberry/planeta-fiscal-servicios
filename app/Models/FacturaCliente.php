<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaCliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_emision',
        'considerado',
        'cliente_id',
        'factura_id',
        'numero_cuenta_id',
    ];

    protected $casts = [
        'considerado' => 'boolean',
    ];

    protected $dates = [
        'fecha_emision',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function numeroCuenta()
    {
        return $this->belongsTo(NumeroCuenta::class);
    }
}
