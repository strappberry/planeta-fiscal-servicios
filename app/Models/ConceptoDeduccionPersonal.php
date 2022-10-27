<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoDeduccionPersonal extends Model
{
    use HasFactory;

    const TIPO_VENTA = 'venta';
    const TIPO_GASTO = 'gasto';

    protected $fillable = [
        'concepto',
        'tipo_factura',
    ];
}
