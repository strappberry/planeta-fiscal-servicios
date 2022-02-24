@extends('adminlte::page')

@section('content')
<div class="pt-5"></div>

<x-adminlte-card class="shadow-none">
    <div class="row">
        <div class="col-sm-4 text-bold">
            {{ __('dashboard.clientes.razon_social') }}
        </div>
        <div class="col-sm-8">
            {{ $cliente->razon_social }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 text-bold">
            {{ __('dashboard.clientes.regimen_fiscal') }}
        </div>
        <div class="col-sm-8">
            {{ $cliente->regimen_fiscal_catalogo }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 text-bold">
            {{ __('dashboard.general.rfc') }}
        </div>
        <div class="col-sm-8">
            {{ $cliente->rfc }}
        </div>
    </div>
</x-adminlte-card>

@livewire(
    'dashboard.clientes.componentes.formulario-fiel',
    ['cliente' => $cliente],
    key('formulario-fiel')
)

@endsection