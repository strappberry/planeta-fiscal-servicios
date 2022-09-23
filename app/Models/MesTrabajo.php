<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesTrabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'bloqueado',
        'configuraciones',
        'cliente_id',
    ];

    protected $casts = [
        'fecha'           => 'date',
        'bloqueado'       => 'boolean',
        'configuraciones' => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
