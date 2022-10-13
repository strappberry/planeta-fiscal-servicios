<?php

namespace App\Models;

use App\Models\Concerns\TieneHistorico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesTrabajo extends Model
{
    use HasFactory;
    use TieneHistorico;

    protected $fillable = [
        'fecha',
        'bloqueado',
        'configuraciones',
        'polizas_validadas',
        'cliente_id',
    ];

    protected $casts = [
        'fecha'             => 'date',
        'bloqueado'         => 'boolean',
        'configuraciones'   => 'array',
        'polizas_validadas' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
