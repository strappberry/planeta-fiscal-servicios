<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaNomina extends Model
{
    use HasFactory;

    protected $fillable = [
        'mes_trabajo',
        'segmento',
        'clave',
        'descripcion',
        'cuenta',
        'columna',
        'cargo',
        'abono',
        'deducible_isr',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
