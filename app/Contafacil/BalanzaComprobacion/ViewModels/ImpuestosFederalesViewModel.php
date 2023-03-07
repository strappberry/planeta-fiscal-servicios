<?php

namespace App\Contafacil\BalanzaComprobacion\ViewModels;

use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Contafacil\Compartido\ViewModels\ViewModel;
use App\Models\Cliente;
use App\Models\SaldoFavorAcreditamiento;
use Carbon\Carbon;

class ImpuestosFederalesViewModel extends ViewModel
{
    public function __construct(
        private Cliente $cliente,
        private Carbon $fecha,
    ) {
    }

    public function cuentas(): array
    {
        $cuentas = [];

        $cuentas[] = [
            'cuenta'      => '213-01',
            'clave'       => 'iva_por_pagar',
            'descripcion' => 'IVA por pagar',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '213-03',
            'clave'       => 'isr_por_pagar',
            'descripcion' => 'ISR por pagar',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        /*
         |----------------------------------------------------------------------
         | Impuestos retenidos de ISR por sueldos y salarios
         |----------------------------------------------------------------------
         */
        $saldo =  SaldoFavorAcreditamiento::porConceptoYFecha(
            SaldosAFavorDatos::IMPUESTOS_RETENIDOS_SUELDOS_Y_SALARIOS,
            $this->fecha
        );
        $cuentas[] = [
            'cuenta'      => '216-01',
            'clave'       => 'impuestos_retenidos_isr_sueldos',
            'descripcion' => 'Impuestos retenidos de ISR por sueldos y salarios',
            'columna'     => 'cargo',
            'cargo'       => $saldo ? $saldo->importe : 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-02',
            'clave'       => 'impuestos_retenidos_isr_asimilados',
            'descripcion' => 'Impuestos retenidos de ISR por asimilados a salarios',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-03',
            'clave'       => 'impuestos_retenidos_isr_arrendamiento',
            'descripcion' => 'Impuestos retenidos de ISR Arrendamiento',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-04',
            'clave'       => 'impuestos_retenidos_isr_servicios_profesionales',
            'descripcion' => 'Impuestos retenidos de ISR Servicios profesionales',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '216-10',
            'clave'       => 'impuestos_retenidos_iva',
            'descripcion' => 'Impuestos retenidos de IVA',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '601-81',
            'clave'       => 'gastos_no_deducibles',
            'descripcion' => 'GASTOS NO DEDUCIBLE',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '601-84',
            'clave'       => 'otros_gastos',
            'descripcion' => 'Otros gastos',
            'columna'     => 'cargo',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '113-01-03',
            'clave'       => 'iva_a_favor',
            'descripcion' => 'IVA a favor',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '113-02-03',
            'clave'       => 'isr_a_favor',
            'descripcion' => 'ISR a favor',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '110-01',
            'clave'       => 'subsidio_al_empleo_por_aplicar',
            'descripcion' => 'Subsidio al empleo por aplicar',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        $cuentas[] = [
            'cuenta'      => '102-01',
            'clave'       => 'bancos_nacional',
            'descripcion' => 'Bancos nacional',
            'columna'     => 'abono',
            'cargo'       => 0,
            'abono'       => 0,
        ];

        return $cuentas;
    }
}
