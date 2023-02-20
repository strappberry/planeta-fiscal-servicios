<?php

namespace App\Models;

use App\Enums\TipoPersona;
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
        'planetafiscal_id',
        'regimenes_fiscales',
    ];

    protected $casts = [
        'obtener_facturas' => 'boolean',
        'regimenes_fiscales' => 'array',
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

    public function getEsPersonaFisicaAttribute()
    {
        return $this->tipoPersona === TipoPersona::FISICA;
    }

    public function getEsPersonaMoralAttribute()
    {
        return $this->tipoPersona === TipoPersona::MORAL;
    }

    public function getTipoPersonaAttribute()
    {
        if (strlen($this->rfc) == 12) {
            return TipoPersona::MORAL;
        }

        return TipoPersona::FISICA;
    }

    public function clavesSat()
    {
        return $this->hasMany(ClaveSat::class);
    }

    public function determinacionCamposEditables()
    {
        return $this->hasMany(DeterminacionCampoEditable::class);
    }

    public function determinacionDelImpuesto()
    {
        return $this->hasMany(DeterminacionImpuesto::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function facturasCliente()
    {
        return $this->hasMany(FacturaCliente::class);
    }

    public function mesesTrabajo()
    {
        return $this->hasMany(MesTrabajo::class);
    }

    public function saldosAFavor()
    {
        return $this->hasMany(SaldoAFavor::class);
    }

    public function solicitudesDescarga()
    {
        return $this->hasMany(SolicitudDescarga::class, 'cliente_id');
    }

    public function balanzasComprobacion()
    {
        return $this->hasMany(BalanzaComprobacionCliente::class);
    }

    public function polizasNominas()
    {
        return $this->hasMany(PolizaNomina::class);
    }

    public function scopeAplicarBusqueda($query, $busqueda)
    {
        if ($busqueda) {
            $query->where('razon_social', 'like', "%{$busqueda}%")
                ->orWhere('rfc', 'like', "%{$busqueda}%");
        }
    }

    public function scopeObtenerFacturasAutomaticamente()
    {
        return $this->where('obtener_facturas', true);
    }

    public function tieneRegimen($regimen): bool
    {
        if (!$this->regimenes_fiscales) return false;

        return in_array($regimen, $this->regimenes_fiscales);
    }

}
