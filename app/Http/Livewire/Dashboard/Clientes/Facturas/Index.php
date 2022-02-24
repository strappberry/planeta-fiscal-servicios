<?php

namespace App\Http\Livewire\Dashboard\Clientes\Facturas;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $cliente;

    public function render()
    {
        $facturas = $this->cliente->facturas()
            ->paginate(30);

        return view(
            'livewire.dashboard.clientes.facturas.index',
            compact('facturas')
        );
    }
}
