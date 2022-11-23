<?php

namespace App\Models;

use App\Enums\TipoDescargaFactura;
use App\Models\Concerns\PerteneceAUnCliente;
use App\Models\QueryBuilders\SolicitudDescargaMasivaQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDescargaMasiva extends Model
{
    use HasFactory;
    use PerteneceAUnCliente;

    protected $fillable = [
        'fecha_inicial',
        'fecha_final',
        'solicitud_id',
        'tipo_solicitud',
        'estado_solicitud',
        'estado_sat',
        'tipo_descarga_facturas',
        'paquetes',
        'cliente_id',
    ];

    protected $casts = [
        'paquetes' => 'array',
    ];

    protected $dates = [
        'fecha_inicial',
        'fecha_final',
    ];

    public function newEloquentBuilder($query)
    {
        return new SolicitudDescargaMasivaQueryBuilder($query);
    }

    protected function tipoDescargaMasiva(): Attribute
    {
        return Attribute::make(
            get: fn ($valor, $atributos) =>
                TipoDescargaFactura::resolverTipoDescargaMasiva($atributos['tipo_descarga_facturas']),
        );
    }

    protected function tipoBusquedaScraper(): Attribute
    {
        return Attribute::make(
            get: fn ($valor, $atributos) =>
                TipoDescargaFactura::resolverTipoBusquedaUUIDS($atributos['tipo_descarga_facturas']),
        );
    }
}
