<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanzaComprobacion extends Model
{
    use HasFactory;

    const TIPO_AUXILIAR = 'auxiliar';

    protected $fillable = [
        'numero_cuenta',
        'descripcion',
        'tipo',
        'formula',
    ];

    protected $casts = [
        'formula' => 'array',
    ];

}
