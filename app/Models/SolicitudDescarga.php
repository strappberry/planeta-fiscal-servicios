<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDescarga extends Model
{
    use HasFactory;

    const STATUS_PENDIENTE         = 'pendiente';
    const STATUS_PROCESANDO        = 'procesando';
    const STATUS_DESCARGANDO       = 'descargando';
    const STATUS_PROCESADO         = 'procesado';
    const STATUS_ERROR_AL_PROCESAR = 'error_al_procesar';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'status',
        'descargas',
        'solicitado_por',
        'descarga_automatica',
        'cliente_id',
    ];

    protected $casts = [
        'descarga_automatica' => 'boolean',
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_fin',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
