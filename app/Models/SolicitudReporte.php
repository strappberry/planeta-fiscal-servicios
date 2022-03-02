<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudReporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'rfc',
        'fecha_inicio',
        'fecha_fin',
        'token',
    ];
}
