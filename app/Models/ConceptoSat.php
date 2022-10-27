<?php

namespace App\Models;

use App\Models\QueryBuilders\ConceptosSatQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoSat extends Model
{
    use HasFactory;

    const TIPO_VENTA = 'venta';
    const TIPO_GASTO = 'gasto';

    protected $fillable = [
        'concepto',
        'tipo_factura',
    ];

    public function newEloquentBuilder($query)
    {
        return new ConceptosSatQueryBuilder($query);
    }
}
