<?php

namespace App\Acciones\Facturas;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\FacturaCliente;

class ResolverFacturaCliente
{
    /**
     * Dada una factura y un cliente se buscará la relación entre estos dos.
     * Si no se encuentra se procedera a crearla, se agregaran automaticamente los
     * datos de fecha emision y pago(si es ppd la fecha de pago será null), tambien
     * se resolverá el tipo de factura (venta o gasto).
     */
    public static function ejecutar(Factura $factura, Cliente $cliente): FacturaCliente
    {
        // La fecha_emision se obtiene de la fecha de emisión de la factura
        // El campo considerado se inicializa en false
        // Si la factura es PPDF se le asigna fecha_pago en null ya que esta deberá ser agregada manualmente
        // Es importante resolver el tipo de factura ya que se usará para generar su poliza individual.

        return FacturaCliente::firstOrCreate(
            [
                'factura_id' => $factura->id,
                'cliente_id' => $cliente->id,
            ],
            [
                'fecha_emision' => $factura->fecha_emision,
                'considerado'   => false,
                'fecha_pago'    => $factura->metodo_pago == 'PUE' ? $factura->fecha_emision : null,
                'tipo_factura'  => ResolverTipoFacturaVentaOGasto::ejecutar($factura, $cliente),
            ]
        );
    }
}
