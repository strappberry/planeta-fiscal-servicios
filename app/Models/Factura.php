<?php

namespace App\Models;

use App\Models\Concerns\TieneComentarios;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    use TieneComentarios;

    protected $fillable = [
        'uuid',
        'rfc_emisor',
        'nombre_emisor',
        'regimen_fiscal_emisor',
        'rfc_receptor',
        'nombre_receptor',
        'uso_cfdi_receptor',
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
        'forma_pago',
        'metodo_pago',
        'moneda',
        'tipo_cambio',
        'retencion_isr',
        'retencion_iva',
        'retencion_ieps',
        'traslado_iva',
        'traslado_ieps',
        'traslados_exentos',
        'otros_impuestos',
        'tasa_cero',
        'monto_comprobacion',
    ];

    protected $casts = [
        'total'              => 'float',
        'subtotal'           => 'float',
        'descuento'          => 'float',
        'xml_procesado'      => 'boolean',
        'complementos'       => 'array',
        'retencion_isr'      => 'float',
        'retencion_iva'      => 'float',
        'retencion_ieps'     => 'float',
        'traslado_iva'       => 'float',
        'traslado_ieps'      => 'float',
        'traslados_exentos'  => 'float',
        'otros_impuestos'    => 'float',
        'tasa_cero'          => 'float',
        'monto_comprobacion' => 'float',
    ];

    protected $dates = [
        'fecha_emision',
        'fecha_certificacion',
        'fecha_proceso_cancelacion',
    ];

    protected $appends = [
        'traslado_iva_sobre_dieciseis',
    ];

    /**
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function comprobanteXml()
    {
        return $this->hasOne(ComprobanteXml::class);
    }

    public function complementoPagos()
    {
        return $this->hasOne(CompPago::class);
    }

    public function complementoNomina()
    {
        return $this->hasOne(CompNomina::class);
    }

    public function facturasCliente()
    {
        return $this->hasMany(FacturaCliente::class);
    }

    /**
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */

    public function scopeAplicarFiltros($query, $filtros)
    {
        $filtros = collect($filtros);

        if ($filtros->has('uuid')) {
            $query->where('uuid', $filtros->get('uuid'));
        }

        if ($filtros->has('fechaInicio') && $filtros->get('fechaInicio')) {
            $query->where('fecha_emision', '>=', $filtros->get('fechaInicio'));
        }

        if ($filtros->has('fechaFin') && $filtros->get('fechaFin')) {
            $query->where('fecha_emision', '<=', $filtros->get('fechaFin'));
        }
    }

    public function scopeAplicarFiltroBuscador($query, $busqueda)
    {
        if ($busqueda) {
            $query
                ->where('uuid', 'like', "%{$busqueda}%")
                ->orWhere('rfc_emisor', 'like', "%{$busqueda}%")
                ->orWhere('nombre_emisor', 'like', "%{$busqueda}%")
                ->orWhere('rfc_receptor', 'like', "%{$busqueda}%")
                ->orWhere('nombre_receptor', 'like', "%{$busqueda}%")
                ->orWhere('estatus_cancelacion', 'like', "%{$busqueda}%")
                ->orWhere('estado_comprobante', 'like', "%{$busqueda}%")
                ->orWhere('descuento', 'like', "%{$busqueda}%")
                ->orWhere('subtotal', 'like', "%{$busqueda}%")
                ->orWhere('total', 'like', "%{$busqueda}%")
                ->orWhere('serie', 'like', "%{$busqueda}%")
                ->orWhere('folio', 'like', "%{$busqueda}%")
                ->orWhere('tipo_comprobante', 'like', "%{$busqueda}%")
                ;
        }
    }

    public function scopeCancelados($query)
    {
        return $query
            ->where('estatus_cancelacion', 'Cancelado')
            ->orWhere('estado_comprobante', 'cancelado');
    }

    public function scopeFacturaPue($query)
    {
        return $query
            ->where('metodo_pago', 'PUE');
    }

    public function scopeVigentes($query)
    {
        return $query
            ->where('estado_comprobante', '=', 'Vigente')
            ->orWhere('estado_comprobante', '=', 'vigente');
    }

    /**
     |--------------------------------------------------------------------------
     | Attributes
     |--------------------------------------------------------------------------
     */

    public function getTrasladoIvaSobreDieciseisAttribute()
    {
        if ($this->traslado_iva > 0) {
            return $this->traslado_iva / 0.16;
        }
        return 0;
    }
}
