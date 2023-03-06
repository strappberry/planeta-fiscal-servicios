<?php

namespace App\Models;

use App\Models\QueryBuilders\PolizaNominaQueryBuilder;
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

    protected $casts = [
        'mes_trabajo'   => 'date',
        'cargo'         => 'float',
        'abono'         => 'float',
        'deducible_isr' => 'float',
    ];

    public function newEloquentBuilder($query)
    {
        return new PolizaNominaQueryBuilder($query);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
