<?php

namespace App\Http\Controllers\Contafacil;

use App\Http\Controllers\Controller;
use App\Models\ConceptoSat;
use Illuminate\Http\Request;

class ConceptosSatController extends Controller
{
    public function obtenerConceptosSat()
    {
        $conceptos = ConceptoSat::all();

        return response()->json([
            'conceptos_sat' => $conceptos,
        ]);
    }
}
