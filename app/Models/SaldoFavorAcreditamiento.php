<?php

namespace App\Models;

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

    public function saldoAFavor()
    {
        return $this->belongsTo(SaldoAFavor::class);
    }
}
