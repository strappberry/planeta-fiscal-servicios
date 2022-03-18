<?php
namespace App\Reportes;

interface ReporteFacturacionPF extends Reporte
{
    public function informacionCliente(): array;
}