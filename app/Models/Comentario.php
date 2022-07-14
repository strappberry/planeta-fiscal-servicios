<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'comentario',
        'usuario_id',
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
