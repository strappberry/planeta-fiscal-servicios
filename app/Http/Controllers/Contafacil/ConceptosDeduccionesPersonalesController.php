<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\ConceptoDeduccionPersonal;
use Illuminate\Http\Request;

class ConceptosDeduccionesPersonalesController extends Controller
{
    public function obtenerConceptosDeduccionesPersonales()
    {
        $conceptos = ConceptoDeduccionPersonal::all();

        return response()->json([
            'conceptos_deducciones_personales' => $conceptos,
        ]);
    }
}
