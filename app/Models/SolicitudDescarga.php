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
    const STATUS_CANCELADO         = 'cancelado';

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

    protected $appends = [
        'fecha_fin_formateada',
        'fecha_inicio_formateada',
    ];

    public function getFechaInicioFormateadaAttribute()
    {
        return $this->fecha_inicio->format('Y-m-d H:i:s');
    }

    public function getFechaFinFormateadaAttribute()
    {
        return $this->fecha_fin->format('Y-m-d H:i:s');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
