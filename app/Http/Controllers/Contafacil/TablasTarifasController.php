<?php

namespace App\Http\Controllers\Contafacil;

use App\Contafacil\TablaTarifa\ViewModels\ConfiguracionTablasViewModel;
use App\Http\Controllers\Controller;
use App\Models\TablaTarifa;
use Illuminate\Http\Request;

class TablasTarifasController extends Controller
{
    public function obtenerTabla(Request $request)
    {
        $this->validate($request, [
            'segmento'    => 'required',
            'anio'        => 'required',
            'clave_tabla' => 'required',
        ]);

        $tablaTarifa = TablaTarifa::query()
            ->buscarTablaTarifa(
                $request->segmento,
                $request->anio,
                $request->clave_tabla
            )
            ->orderBy('limite_inferior', 'asc')
            ->get();

        return response()->json([
            'tabla' => $tablaTarifa,
        ]);
    }

    public function configuracionTablas()
    {
        $tablasTarifas = collect(config('tarifas.tablas'));
        $modelo        = new ConfiguracionTablasViewModel($tablasTarifas);

        return response()->json([
            'modelo' => $modelo->toArray(),
        ]);
    }

    public function guardarTablaTarifa(Request $request)
    {
        $this->validate($request, [
            'segmento'    => 'required',
            'anio'        => 'required',
            'clave_tabla' => 'required',
            'tabla'       => 'required|array',
        ]);

        TablaTarifa::query()
            ->buscarTablaTarifa(
                $request->segmento,
                $request->anio,
                $request->clave_tabla
            )->delete();

        foreach($request->tabla as $linea) {
            if (!$linea[0] && !$linea[1] && !$linea[2] && !$linea[3]) continue;

            TablaTarifa::create([
                'segmento'    => $request->segmento,
                'anio'        => $request->anio,
                'clave_tabla' => $request->clave_tabla,
                'limite_inferior'      => $linea[0],
                'limite_superior'      => $linea[1],
                'cuota_fija'           => $linea[2],
                'porcentaje_excedente' => $linea[3],
            ]);
        }

        return response()->json([
            'mensaje' => 'Tarifa guardada correctamente',
        ]);
    }

}
