<?php

namespace App\Http\Livewire\Dashboard\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $busqueda = '';

    public function updatedBusqueda()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clientes = Cliente::query()
            ->aplicarBusqueda($this->busqueda)
            ->orderBy('rfc')
            ->paginate(40);

        return view('livewire.dashboard.clientes.index', compact('clientes'));
    }
}
