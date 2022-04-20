<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudArchivo extends Model
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
