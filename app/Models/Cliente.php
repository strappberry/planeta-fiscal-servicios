<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    const LIVEWIRE_RULES = [
        'formulario.razon_social' => 'required',
        'formulario.rfc' => 'required',
        'formulario.regimen_fiscal' => 'required',
    ];

    use HasFactory;

    protected $fillable = [
        'razon_social',
        'rfc',
        'regimen_fiscal',
        'obtener_facturas',
    ];

    protected $casts = [
        'obtener_facturas' => 'boolean',
    ];

    public function getRegimenFiscalCatalogoAttribute()
    {
        $regimenes = config('regimenes');
        $clave = $this->regimen_fiscal;
        $regimen = array_filter($regimenes, function ($regimen) use ($clave) {
            return $regimen['id'] == $clave;
        });
        $regimen = array_values($regimen);

        if (count($regimen) > 0) {
            return $regimen[0]['descripcion'];
        }

        return '';
    }

    public function clavesSat()
    {
        return $this->hasMany(ClaveSat::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function scopeObtenerFacturasAutomaticamente()
    {
        return $this->where('obtener_facturas', true);
    }

}
