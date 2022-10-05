<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    use HasFactory;

    protected $fillable = [
        'comentario',
        'usuario_planeta_fiscal',
        'relacionable_id',
        'relacionable_type',
    ];

    protected $hidden = [
        'relacionable_id',
        'relacionable_type',
    ];

    public function relacionable()
    {
        return $this->morphTo();
    }
}
