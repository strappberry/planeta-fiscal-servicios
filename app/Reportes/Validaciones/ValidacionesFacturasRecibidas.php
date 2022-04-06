<?php
namespace App\Reportes\Validaciones;

use App\Enums\TipoPersona;

class ValidacionesFacturasRecibidas
{

    /**
     * Validar si el rfc corresponde con el regimen fiscal
     *
     * @param string $rfc
     * @param string $regimenFiscal
     * @return bool
     */
    public static function validacionRfcContraRegimenFiscal(
        string $rfc,
        string $regimenFiscal
    ): bool {
        $regimenesPersonaFisica = [
            605, 606, 607, 608, 610, 611, 612, 614, 615, 616, 621, 625, 626,
        ];
        $regimenesPersonaMoral = [
            601, 603, 620, 622, 623, 624, 626,
        ];

        if (
            TipoPersona::esPersonFisica($rfc) &&
            in_array($regimenFiscal, $regimenesPersonaFisica)
        ) return true;

        if (
            TipoPersona::esPersonMoral($rfc) &&
            in_array($regimenFiscal, $regimenesPersonaMoral)
        ) return true;

        return false;
    }

    /**
     * Validar el usoCfdi
     *
     * @param string $usoCfdi
     * @return void
     */
    public static function usoCfdiCorrecto($usoCfdi): bool
    {
        $tiposInvalidos = [
            'P01',
        ];

        return !in_array($usoCfdi, $tiposInvalidos);
    }

}