<form wire:submit.prevent="guardarFiel">
    <x-adminlte-card class="shadow-none" title="{{ __('dashboard.clientes.fiel') }}" theme="navy" theme-mode="outline">

        @if ($this->ultimaFiel)
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ __('dashboard.sat.fecha_de_caducidad') }}</strong>
                            {{ $this->ultimaFiel->caducidad->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
        @endif

        <x-app-lw-input
            type="file"
            accept=".cer"
            identificador="cer"
            etiqueta="dashboard.sat.archivo_cer"
            modelo="cer"
        />
        
        <x-app-lw-input
            type="file"
            accept=".key"
            identificador="key"
            etiqueta="dashboard.sat.archivo_key"
            modelo="key"
        />

        <x-app-lw-input
            identificador="contrasena"
            etiqueta="dashboard.sat.contrasena_fiel"
            modelo="formulario.password"
        />

        @if ($mensajeError)
            <div class="alert alert-danger" role="alert">
                <strong>{{ $mensajeError }}</strong>
            </div>
        @endif

        <div class="text-right">
            <button type="submit" class="btn btn-dark bg-navy">
                {{ __('dashboard.sat.guardar_fiel') }}
            </button>
        </div>

    </x-adminlte-card>
</form>
