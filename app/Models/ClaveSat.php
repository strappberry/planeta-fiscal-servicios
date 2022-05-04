<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ClaveSat extends Model
{
    const LIVEWIRE_RULES = [
        'formulario.password' => 'required',
    ];
    const TIPO_FIEL = 'fiel';

    use HasFactory;

    protected $fillable = [
        'cer',
        'key',
        'password',
        'caducidad',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'password' => 'encrypted',
    ];

    protected $dates = [
        'caducidad',
    ];

    public function scopeEsFiel($query)
    {
        return $query->where('tipo', 'fiel');
    }

    public function scopeEsCSD($query)
    {
        return $query->where('tipo', 'csd');
    }

    public function scopeSinCaducar($query)
    {
        return $query->where('caducidad', '>', now());
    }

    public function eliminarArchivos()
    {
        if (Storage::exists($this->cer)) {
            Storage::delete($this->cer);
        }
        if (Storage::exists($this->key)) {
            Storage::delete($this->key);
        }
    }
}
