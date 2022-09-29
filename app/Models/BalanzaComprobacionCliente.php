<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanzaComprobacionCliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'saldo_inicial',
        'cargo',
        'abono',
        'saldo_final',
        'balanza_comprobacion_id',
        'cliente_id',
    ];

    protected $casts = [
        'saldo_inicial' => 'float',
        'cargo'         => 'float',
        'abono'         => 'float',
        'saldo_final'   => 'float',
    ];

    public function balanzaComprobacion()
    {
        return $this->belongsTo(BalanzaComprobacion::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
