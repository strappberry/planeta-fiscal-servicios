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
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::PERSONA_FISICA_ACTIVIDAD_EMPRESARIAL)) {
            return self::personaFisicaActividadEmpresarial($cliente, $fecha);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::PLATAFORMAS_TECNOLOGICAS)) {
            return self::actualizarTipoIngreso($cliente, $fecha, TipoIngreso::ACTIVIDAD_EMPRESARIAL);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::ARRENDAMIENTO)) {
            return self::actualizarTipoIngreso($cliente, $fecha, TipoIngreso::ARRENDAMIENTO);
        }
        if ($cliente->esPersonaFisica && $cliente->tieneRegimen(RegimenFiscal::RESICO)) {
            return self::actualizarTipoIngreso($cliente, $fecha, TipoIngreso::RESICO_PF);
        }
    }

    private static function personaFisicaActividadEmpresarial(Cliente $cliente, Carbon $fecha)
    {
        $posibilidadDeArrendamiento = $cliente->tieneRegimen(RegimenFiscal::ARRENDAMIENTO);
        $consulta = $cliente->facturasCliente()
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esVenta();

        if ($posibilidadDeArrendamiento) {
            $consulta = $consulta->where('tipo_ingreso', '!=',  TipoIngreso::ARRENDAMIENTO);
        }

        $consulta->update([
            'tipo_ingreso' => TipoIngreso::ACTIVIDAD_EMPRESARIAL,
        ]);
    }

    private static function actualizarTipoIngreso(Cliente $cliente, Carbon $fecha, string $tipoIngreso)
    {
        $cliente->facturasCliente()
            ->dentroFechaPago(
                $fecha->copy()->startOfMonth(),
                $fecha->copy()->endOfMonth()
            )
            ->esVenta()
            ->update([
                'tipo_ingreso' => $tipoIngreso,
            ]);
    }
}
