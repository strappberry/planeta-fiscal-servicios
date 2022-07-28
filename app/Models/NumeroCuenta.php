<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroCuenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_cuenta',
        'descripcion',
        'ventas',
        'gastos',
    ];

    protected $casts = [
        'considerado' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function facturasCliente()
    {
        return $this->hasMany(FacturaCliente::class);
    }
}
