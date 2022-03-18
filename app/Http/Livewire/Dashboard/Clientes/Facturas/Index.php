<?php

namespace App\Http\Livewire\Dashboard\Clientes\Facturas;

use App\Enums\ReportesEnum;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $cliente;
    public $fechaInicio = '';
    public $fechaFin = '';

    public function mount()
    {
        $this->fechaInicio = date('Y-m-d', strtotime('first day of last month'));
        $this->fechaFin = date('Y-m-d', strtotime('last day of last month'));
    }

    public function updatedFechaInicio()
    {
        $this->resetPage();
    }

    public function updatedFechaFin()
    {
        $this->resetPage();
    }

    public function getEnlaceReporteSimplificadoProperty()
    {
        return route('admin.reportes_web.reporte', [
            'tipo' => ReportesEnum::SIMPLIFICADO,
            'rfc' => $this->cliente->rfc,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
        ]);
    }

    public function getEnlaceReporteElectronicaProperty()
    {
        return route('admin.reportes_web.reporte', [
            'tipo' => ReportesEnum::ELECTRONICA,
            'rfc' => $this->cliente->rfc,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
        ]);
    }

    public function render()
    {
        $facturas = $this->cliente->facturas()
            ->aplicarFiltros([
                'fechaInicio' => $this->fechaInicio,
                'fechaFin' => $this->fechaFin,
            ])
            ->orderBy('facturas.fecha_emision', 'asc')
            ->paginate(30);

        return view(
            'livewire.dashboard.clientes.facturas.index',
            compact('facturas')
        );
    }
}
