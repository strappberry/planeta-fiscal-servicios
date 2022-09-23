<?php

namespace App\Http\Controllers\Contafacil;

use App\Acciones\Clientes\ResolverClientePlanetaFiscal;
use App\Contafacil\Facturas\ViewModels\FacturaCuentasManualesViewModel;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\FacturaCliente;
use App\Models\NumeroCuenta;
use Illuminate\Http\Request;

/* TODO:  cambiar la relacion con cliente */
class FacturasNumeroCuentaController extends Controller
{
    public function obtenerCuentasManuales(string $clienteId, Factura $factura)
    {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$facturaCliente) {
            $facturaCliente = FacturaCliente::create([
                'factura_id'    => $factura->id,
                'cliente_id'    => $clienteId,
                'fecha_emision' => $factura->fecha_emision,
                'consignado'    => false,
            ]);
        }

        $modelo = new FacturaCuentasManualesViewModel($facturaCliente);

        return response()->json([
            'modelo' => $modelo->toArray(),
        ]);
    }

    public function agregarNumeroCuentaManual(Request $request, string $clienteId, Factura $factura)
    {
        $this->validate($request, [
            'numero_cuenta' => 'required',
            'monto'         => 'required',
        ]);

        $numeroCuenta = NumeroCuenta::find($request->numero_cuenta);
        if (!$numeroCuenta) {
            return response()->json([
                'success' => false,
                'message' => 'El número de cuenta no existe',
            ]);
        }

        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$facturaCliente) {
            $facturaCliente = FacturaCliente::create([
                'factura_id'    => $factura->id,
                'cliente_id'    => $clienteId,
                'fecha_emision' => $factura->fecha_emision,
                'consignado'    => false,
            ]);
        }

        $facturaCliente->numerosCuentas()->syncWithoutDetaching([
            $numeroCuenta->id => [
                'monto' => $request->monto,
            ],
        ]);

        return response()->json([
            'agregado' => true,
        ]);
    }

    public function eliminarNumeroCuentaManual(
        Request $request,
        string $clienteId,
        Factura $factura,
        NumeroCuenta $numeroCuenta
    ) {
        $cliente = ResolverClientePlanetaFiscal::ejecutar($clienteId);

        $facturaCliente = FacturaCliente::query()
            ->where('factura_id', $factura->id)
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$facturaCliente) {
            $facturaCliente = FacturaCliente::create([
                'factura_id'    => $factura->id,
                'cliente_id'    => $clienteId,
                'fecha_emision' => $factura->fecha_emision,
                'consignado'    => false,
            ]);
        }

        $facturaCliente->numerosCuentas()->detach($numeroCuenta->id);

        return response()->json([
            'eliminado' => true,
        ]);
    }
}
