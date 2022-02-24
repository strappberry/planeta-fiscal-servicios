<div class="form-group row">
    <label for="{{ $identificador }}" class="col-form-label col-sm-4">
        {{ __($etiqueta) }}
    </label>
    <div class="col-sm-8">
        <select
            class="form-control @error($modelo)is-invalid @enderror"
            name="{{ $identificador }}"
            id="{{ $identificador }}"
            wire:model="{{ $modelo }}"
        >
        {!! $slot !!}
        </select>
        @error($modelo)
            <small id="error-{{ $identificador }}" class="text-danger">
                {{ $message }}
            </small>
        @enderror
        @if ($ayuda)
            <small id="help-{{ $identificador }}" class="form-text text-muted">
                {!! $ayuda !!}
            </small>
        @endif
    </div>
</div>