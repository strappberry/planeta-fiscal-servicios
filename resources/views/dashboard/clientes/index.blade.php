@extends('adminlte::page')

@section('content')

<div class="text-right mb-4 pt-5">
    <a href="{{ route('admin.clientes.crear') }}" class="btn btn-dark bg-navy">
        {{ __('dashboard.general.agregar_nuevo') }}
    </a>
</div>

@livewire('dashboard.clientes.index', [], key('listado-clientes'))

@endsection