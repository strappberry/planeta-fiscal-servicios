<?php

namespace App\Models;

use App\Contafacil\Compartido\Datos\SaldosAFavorDatos;
use App\Models\QueryBuilders\SaldoFavorAcreditamientoQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoFavorAcreditamiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'remanente_historico',
        'importe',
        'periodo',
        'concepto',
        'saldo_a_favor_id',
    ];

    protected $casts = [
        'remanente_historico' => 'float',
        'importe' => 'float',
        'periodo' => 'date',
    ];

    protected $appends = [
        'concepto_descripcion',
    ];

    public function newEloquentBuilder($query)
    {
        return new SaldoFavorAcreditamientoQueryBuilder($query);
    }

    public function saldoAFavor()
    {
        return $this->belongsTo(SaldoAFavor::class);
    }

    public function getConceptoDescripcionAttribute()
    {
        $conceptos = collect(SaldosAFavorDatos::CONCEPTOS_ACREDITAMIENTO);

        $concepto = $conceptos->firstWhere('clave', $this->concepto);

        return $concepto ? $concepto['descripcion'] : '';
    }
}
