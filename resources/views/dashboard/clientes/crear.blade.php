@extends('adminlte::page')

@section('content')
<div class="row pt-5">
    <div class="col-6 offset-3">
        <x-adminlte-card class="shadow-none">
            @livewire('dashboard.clientes.formulario', [], key('crear-cliente'))
        </x-adminlte-card>
    </div>
</div>
@endsection