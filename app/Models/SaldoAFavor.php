<?php

namespace App\Models;

use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoAFavor extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_operacion',
        'origen',
        'tipo',
        'fecha',
        'fecha_presentacion',
        'saldo_original',
        'suma_comp_acred_ejer_ant',
        'cliente_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_presentacion' => 'date',
        'saldo_original' => 'float',
        'suma_comp_acred_ejer_ant' => 'float',
    ];

    protected $appends = [
        'remanente',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function getRemanenteAttribute()
    {
        return $this->saldo_original - $this->suma_comp_acred_ejer_ant;
    }

    public function getOrigenDescripcionAttribute()
    {
        $origenes = collect(SaldosAFavorDatos::ORIGEN);

        $origen = $origenes->firstWhere('clave', $this->origen);

        return $origen ? $origen['descripcion'] : '';
    }
}
