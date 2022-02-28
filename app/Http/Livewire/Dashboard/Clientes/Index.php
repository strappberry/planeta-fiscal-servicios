<?php

namespace App\Http\Livewire\Dashboard\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $clientes = Cliente::query()
            ->orderBy('rfc')
            ->paginate(20);

        return view('livewire.dashboard.clientes.index', compact('clientes'));
    }
}
