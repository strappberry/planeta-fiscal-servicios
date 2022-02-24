<x-adminlte-card class="shadow-none">

    <div class="table-responsive">
        <table class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>{{ __('dashboard.clientes.razon_social') }}</th>
                    <th>{{ __('dashboard.general.rfc') }}</th>
                    <th>{{ __('dashboard.clientes.regimen_fiscal') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->razon_social }}</td>
                        <td>{{ $cliente->rfc }}</td>
                        <td>{{ $cliente->regimen_fiscal_catalogo }}</td>
                        <td class="text-right">
                            <a
                                href="{{ route('admin.clientes.facturas.index', $cliente->id) }}" 
                                class="btn btn-secondary btn-sm"
                            >
                                {{ __('dashboard.general.facturas') }}
                            </a>
                            <a
                                href="{{ route('admin.clientes.configuracion', $cliente->id) }}" 
                                class="btn btn-secondary btn-sm"
                            >
                                {{ __('dashboard.general.configuracion') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-adminlte-card>