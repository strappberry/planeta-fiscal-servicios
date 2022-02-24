<?php

namespace App\Http\Livewire\Dashboard\Clientes;

use App\Models\Cliente;
use Livewire\Component;

class Formulario extends Component
{
    protected $rules = Cliente::LIVEWIRE_RULES;

    public $formulario = [
        'razon_social' => '',
        'rfc' => '',
        'regimen_fiscal' => '',
        'obtener_facturas' => 0,
    ];

    public function guardarCliente()
    {
        $this->validate();

        $cliente = Cliente::create($this->formulario);

        return redirect()->route('admin.clientes.index');
    }

    public function render()
    {
        $regimenesFiscales = config('regimenes');

        return view('livewire.dashboard.clientes.formulario', compact('regimenesFiscales'));
    }
}
