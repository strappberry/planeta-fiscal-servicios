<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanzaComprobacion extends Model
{
    use HasFactory;

    const TIPO_AUXILIAR = 'auxiliar';
    const TIPO_MAYOR    = 'mayor';

    protected $fillable = [
        'numero_cuenta',
        'descripcion',
        'tipo',
        'formula',
        'balanza_comprobacion_id',
    ];

    protected $casts = [
        'formula' => 'array',
    ];

    public function auxiliares()
    {
        return $this->hasMany(BalanzaComprobacion::class);
    }

    public function cuentasClientes()
    {
        return $this->hasMany(BalanzaComprobacionCliente::class);
    }
}
