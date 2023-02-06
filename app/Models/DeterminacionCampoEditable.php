<?php

namespace App\Models;

use App\Models\QueryBuilders\DeterminacionCampoEditableQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeterminacionCampoEditable extends Model
{
    use HasFactory;

    protected $fillable = [
        'mes_trabajo',
        'clave',
        'valor',
        'regimen',
        'cliente_id',
    ];

    protected $casts = [
        'mes_trabajo' => 'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function newEloquentBuilder($query)
    {
        return new DeterminacionCampoEditableQueryBuilder($query);
    }
}
