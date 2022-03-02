<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'rfc_emisor',
        'nombre_emisor',
        'rfc_receptor',
        'nombre_receptor',
        'fecha_emision',
        'fecha_certificacion',
        'pac_certifico',
        'efecto_comprobante',
        'estatus_cancelacion',
        'estado_comprobante',
        'estatus_proceso_cancelacion',
        'fecha_proceso_cancelacion',
        'descuento',
        'subtotal',
        'total',
        'serie',
        'folio',
        'tipo_comprobante',
        'complementos',
        'xml_procesado',
        'cliente_id',
    ];

    protected $casts = [
        'xml_procesado' => 'boolean',
        'complementos' => 'array',
    ];

    protected $dates = [
        'fecha_emision',
        'fecha_certificacion',
        'fecha_proceso_cancelacion',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function comprobanteXml()
    {
        return $this->hasOne(ComprobanteXml::class);
    }

    public function scopeVigentes($query)
    {
        return $query
            ->where('estado_comprobante', '=', 'Vigente')
            ->orWhere('estado_comprobante', '=', 'vigente');
    }
}
