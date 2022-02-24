<div class="form-group row">
    <label for="{{ $identificador }}" class="col-form-label col-sm-4">
      {{ __($etiqueta) }}
    </label>
    <div class="col-sm-8">
      <input
          class="form-control @error($modelo)is-invalid @enderror"
          id="{{ $identificador }}"
          name="{{ $identificador }}"
          wire:model="{{ $modelo }}"
          placeholder="{{ __($etiqueta) }}"
          {{ $attributes }}
      >
      @error($modelo)
          <small id="error-{{ $identificador }}" class="text-danger">{{ $message }}</small>
      @enderror
    </div>
</div>