<?php

namespace App\Models;

use App\Models\QueryBuilders\CampoEditableQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampoEditable extends Model
{
    use HasFactory;

    const MODULO_IMPUESTOS_FEDERALES = 'impuestos-federales';

    protected $fillable = [
        'mes_trabajo',
        'modulo',
        'campo',
        'valor',
        'cliente_id',
    ];

    protected $casts = [
        'mes_trabajo' => 'date',
    ];

    public function newEloquentBuilder($query)
    {
        return new CampoEditableQueryBuilder($query);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
