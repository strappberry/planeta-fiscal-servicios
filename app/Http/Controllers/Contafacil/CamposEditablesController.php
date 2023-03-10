<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\CamposEditables\CrearActualizarCampoEditableAccion;
use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Http\Controllers\Controller;
use App\Models\CampoEditable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CamposEditablesController extends Controller
{
    public function actualizarCampoEditable(Request $request, $clienteId, $fecha)
    {
        $modulos = [
            CampoEditable::MODULO_IMPUESTOS_FEDERALES,
        ];

        $this->validate($request, [
            'modulo' => "required|in:" . implode(',', $modulos),
            'campo'  => 'required|string',
            'valor'  => 'required|string',
        ]);

        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);
        $fecha   = Carbon::parse($fecha)->startOfMonth();

        $campoEditable = CrearActualizarCampoEditableAccion::ejecutar(
            $cliente,
            $fecha,
            $request->modulo,
            $request->campo,
            $request->valor
        );

        return response()->json([
            'mensaje' => 'Campo editable actualizado correctamente',
            'campo'   => $campoEditable->toArray(),
        ]);
    }
}
