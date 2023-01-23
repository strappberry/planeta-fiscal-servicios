<?php

namespace App\Acciones\Facturas;

use App\Contafacil\Facturas\ViewModels\PolizaAutomaticaFacturaViewModel;
use App\Contafacil\Facturas\ViewModels\ValidacionPolizaAutomaticaFacturaViewModel;
use App\Models\Cliente;
use App\Models\Factura;
use Carbon\Carbon;

class ValidarPolizasMes
{
    /**
     * Dada una fecha y un cliente se calculan las validaciones de todas las polizas del mes,
     * se guarda en base de datos si los datos son validos o no.
     */
    public static function ejecutar(
        Carbon $fechaInicio,
        Carbon $fechaFin,
        Cliente $cliente
    ) {
        $facturas = Factura::query()
            ->whereBetween('fecha_emision', [
                $fechaInicio,
                $fechaFin,
            ])
            ->where(function ($query) use ($cliente) {
                $query->where('rfc_emisor', $cliente->rfc)
                    ->orWhere('rfc_receptor', $cliente->rfc);
            })
            ->whereIn('tipo_comprobante', ['I', 'E', 'i', 'e'])
            ->orderBy('fecha_emision')
            ->get();

        foreach ($facturas as $factura) {
            $facturaCliente = ResolverFacturaCliente::ejecutar($factura, $cliente);
            $modelo         = new PolizaAutomaticaFacturaViewModel($facturaCliente);
            $validaciones   = (new ValidacionPolizaAutomaticaFacturaViewModel($modelo))->toArray();

            $facturaCliente->poliza_valida = $validaciones['validaciones']['validacion'];
            $facturaCliente->save();

            VerificarFacturaClienteDeducible::ejecutar($facturaCliente);
        }

        VerificarYActualizarTipoIngresoPorRegimen::ejecutar($cliente, $fechaInicio);
    }
}
