<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaTarifa extends Model
{
    use HasFactory;

    protected $fillable = [
        'segmento',
        'anio',
        'clave_tabla',
        'limite_inferior',
        'limite_superior',
        'cuota_fija',
        'porcentaje_excedente',
    ];

    protected $casts = [
        'anio'                 => 'integer',
        'limite_inferior'      => 'float',
        'limite_superior'      => 'float',
        'cuota_fija'           => 'float',
        'porcentaje_excedente' => 'float',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    /*
     |--------------------------------------------------------------------------
     | Attributes
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */

    public function scopeBuscarTablaTarifa($query, string $segmento, $anio, $claveTabla)
    {
        return $query->where('segmento', $segmento)
            ->where('anio', $anio)
            ->where('clave_tabla', $claveTabla);
    }

    public function scopeMayorIgualA($query, $monto)
    {
        return $query->where('limite_inferior', '>=', $monto);
    }
}
