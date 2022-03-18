<x-adminlte-card class="shadow-none">

    <div class="mb-4 row">
        <div class="col-md-4"></div>
        <div class="col-md-2">
            <input
                type="date"
                class="form-control"
                wire:model="fechaInicio"
            />
        </div>
        <div class="col-md-2">
            <input
                type="date"
                class="form-control"
                wire:model="fechaFin"
            />
        </div>
        <div class="col-md-2 text-right">
            <a
                href="{{ $this->enlaceReporteSimplificado }}"
                target="_blank"
                class="btn btn-dark bg-navy"
            >
                <i class="fas fa-download"></i> Simplificado
            </a>
        </div>
        <div class="col-md-2 text-right">
            <a
                href="{{ $this->enlaceReporteElectronica }}"
                target="_blank"
                class="btn btn-dark bg-navy"
            >
                <i class="fas fa-download"></i> Electronica
            </a>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-sm table-striped table-borderless">
            <thead>
                <tr>
                    <th>{{ __('dashboard.facturas.uuid') }}</th>
                    <th>{{ __('dashboard.facturas.rfc_emisor') }}</th>
                    <th>{{ __('dashboard.facturas.nombre_emisor') }}</th>
                    <th>{{ __('dashboard.facturas.rfc_receptor') }}</th>
                    <th>{{ __('dashboard.facturas.nombre_receptor') }}</th>
                    <th>{{ __('dashboard.facturas.fecha_emision') }}</th>
                    <th>{{ __('dashboard.facturas.fecha_certificacion') }}</th>
                    <th>{{ __('dashboard.facturas.estado_comprobante') }}</th>
                    <th>{{ __('dashboard.facturas.subtotal') }}</th>
                    <th>{{ __('dashboard.facturas.descuento') }}</th>
                    <th>{{ __('dashboard.facturas.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($facturas as $factura)
                    <tr>
                        <td>{{ $factura->uuid }}</td>
                        <td>{{ $factura->rfc_emisor }}</td>
                        <td>{{ $factura->nombre_emisor }}</td>
                        <td>{{ $factura->rfc_receptor }}</td>
                        <td>{{ $factura->nombre_receptor }}</td>
                        <td>{{ $factura->fecha_emision }}</td>
                        <td>{{ $factura->fecha_certificacion }}</td>
                        <td>{{ $factura->estado_comprobante }}</td>
                        <td class="text-right">{{ number_format($factura->subtotal, 2) }}</td>
                        <td class="text-right">{{ number_format($factura->descuento, 2) }}</td>
                        <td class="text-right">{{ number_format($factura->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $facturas->links() }}

</x-adminlte-card>