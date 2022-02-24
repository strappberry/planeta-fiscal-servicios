<form wire:submit.prevent="guardarCliente">

    <x-app-lw-input
        autofocus
        identificador="razon_social"
        etiqueta="dashboard.clientes.razon_social"
        modelo="formulario.razon_social"
    />

    <x-app-lw-input
        identificador="rfc"
        etiqueta="dashboard.general.rfc"
        modelo="formulario.rfc"
    />

    <x-app-lw-select
        identificador="regimen_fiscal"
        etiqueta="dashboard.clientes.regimen_fiscal"
        modelo="formulario.regimen_fiscal"
    >
        <option value="">
            -- {{ __('dashboard.clientes.seleccione_regimen_fiscal') }}
        </option>

        @foreach ($regimenesFiscales as $regimenFiscal)
            <option value="{{ $regimenFiscal['id'] }}">
                {{ $regimenFiscal['id'] }} - {{ $regimenFiscal['descripcion'] }}
            </option>
        @endforeach
    </x-app-lw-select>
    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <div class="form-check">
                    <label class="form-check-label">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        value="1"
                        wire:model="formulario.obtener_facturas"
                      >
                        {{ __('dashboard.clientes.obtener_facturas') }}
                    </label>
                  </div>
            </div>
        </div>
    </div>
    
    <div class="text-right">
        <button type="submit" class="btn btn-dark bg-navy">
            {{ __('dashboard.general.guardar') }}
        </button>
    </div>

</form>