<?php

namespace App\Acciones\Facturas;

use App\Enums\RegimenFiscal;
use App\Enums\TipoIngreso;
use App\Models\Cliente;
use Carbon\Carbon;

class VerificarYActualizarTipoIngresoPorRegimen
{
    public static function ejecutar(
        Cliente $cliente,
        Carbon $fecha
    ) {
        $ventasCobradas = $cliente->facturasCliente()
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esVenta()
            ->get();

        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::PERSONA_FISICA_ACTIVIDAD_EMPRESARIAL)) {
            return self::personaFisicaActividadEmpresarial($cliente, $ventasCobradas);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::PLATAFORMAS_TECNOLOGICAS)) {
            return self::plataformasTeconologicas($cliente, $ventasCobradas);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::ARRENDAMIENTO)) {
            return self::arrendamiento($cliente, $ventasCobradas);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::RESICO)) {
            return self::resico($cliente, $ventasCobradas);
        }
    }

    private static function personaFisicaActividadEmpresarial(Cliente $cliente, $facturasCliente)
    {
        $posibilidadDeArrendamiento = $cliente->tieneRegimen(RegimenFiscal::ARRENDAMIENTO);

        $facturasCliente->each(function ($facturaCliente) use ($posibilidadDeArrendamiento) {
            if ($posibilidadDeArrendamiento && $facturaCliente->tipo_ingreso == TipoIngreso::ARRENDAMIENTO) {
                return;
            }

            $facturaCliente->tipo_ingreso = TipoIngreso::ACTIVIDAD_EMPRESARIAL;
            $facturaCliente->save();
        });
    }

    private static function arrendamiento(Cliente $cliente, $facturasCliente)
    {
        $facturasCliente->each(function ($facturaCliente) {
            $facturaCliente->tipo_ingreso = TipoIngreso::ARRENDAMIENTO;
            $facturaCliente->save();
        });
    }

    public static function plataformasTeconologicas(Cliente $cliente, $facturasCliente)
    {
        $facturasCliente->each(function ($facturaCliente) {
            $facturaCliente->tipo_ingreso = TipoIngreso::ACTIVIDAD_EMPRESARIAL;
            $facturaCliente->save();
        });
    }

    private static function resico(Cliente $cliente, $facturasCliente)
    {
        $facturasCliente->each(function ($facturaCliente) {
            $facturaCliente->tipo_ingreso = '';
            $facturaCliente->save();
        });
    }
}
