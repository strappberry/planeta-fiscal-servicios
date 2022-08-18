<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancosProyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'cliente_id',
        'tipo',
        'banco_id',
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}
