@extends('adminlte::page')

@section('content')
    <div class="pt-5"></div>

    <div class="text-right mb-4">
        <a href="{{ route('admin.clientes.facturas.descargarFacturas', $cliente->id) }}" class="btn btn-dark bg-navy">
            <i class="fas fa-download"></i>
            {{ __('dashboard.general.descargar') }}
        </a>
    </div>

    @livewire(
        'dashboard.clientes.facturas.index',
        ['cliente' => $cliente],
        key('facturas-cliente')
    )

@endsection