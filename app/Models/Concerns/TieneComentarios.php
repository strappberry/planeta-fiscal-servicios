<?php

namespace App\Models\Concerns;

use App\Models\Comentario;

trait TieneComentarios
{
    public function comentarios()
    {
        return $this->morphMany(Comentario::class, 'relacionable');
    }
}
