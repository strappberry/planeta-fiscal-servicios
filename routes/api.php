<?php

use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\FacturasController;
use App\Http\Controllers\Api\SolicitudesFacturaController;
use App\Http\Controllers\ArchivosController;
use App\Http\Controllers\Contafacil\BalanzaComprobacionController;
use App\Http\Controllers\Contafacil\BancosController;
use App\Http\Controllers\Contafacil\BancosProyectosController;
use App\Http\Controllers\Contafacil\CamposEditablesController;
use App\Http\Controllers\Contafacil\ComentariosController;
use App\Http\Controllers\Contafacil\ClienteController as ContafacilClienteController;
use App\Http\Controllers\Contafacil\ComplementosController;
use App\Http\Controllers\Contafacil\ConceptosDeduccionesPersonalesController;
use App\Http\Controllers\Contafacil\ConceptosSatController;
use App\Http\Controllers\Contafacil\FacturasClienteController;
use App\Http\Controllers\Contafacil\FacturasController as ContafacilFacturasController;
use App\Http\Controllers\Contafacil\FacturasNumeroCuentaController;
use App\Http\Controllers\Contafacil\GastosController;
use App\Http\Controllers\Contafacil\MesTrabajoController;
use App\Http\Controllers\Contafacil\NumerosCuentasController;
use App\Http\Controllers\Contafacil\PolizasNominasController;
use App\Http\Controllers\Contafacil\SaldosAFavorController;
use App\Http\Controllers\Contafacil\TablasTarifasController;
use App\Http\Controllers\Contafacil\TipoIngresoController;
use App\Http\Controllers\Contafacil\VentasController;
use App\Http\Controllers\ReportesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('facturas')->group(function () {
        Route::get('/buscar-facturas', [FacturasController::class, 'buscarFacturas']);

        Route::get('listar-solicitudes-descarga', [SolicitudesFacturaController::class, 'listarSolicitudes']);
        Route::post('solicitar-descarga', [SolicitudesFacturaController::class, 'crearSolicitudDescarga']);
    });

    Route::prefix('clientes')->group(function () {
        Route::post('/recibir-clientes', [ClientesController::class, 'recibirClientes']);
        Route::post('/subir-fiel', [ClientesController::class, 'subirFiel']);
        Route::get('/informacion-cliente/{rfc}', [ClientesController::class, 'informacionCliente']);
    });

    Route::prefix('archivos')->group(function() {
        Route::post(
            'solicitar-archivos',
            [ArchivosController::class, 'crearSolicitudArchivos']
        );
    });

    Route::prefix('reportes')->group(function() {
        Route::post(
            'solicitar-reporte',
            [ReportesController::class, 'crearSolicitudReporte']
        );
    });

    Route::prefix('contafacil')->as('contafacil')->group(function() {
        Route::prefix('ventas')->group(function () {
            Route::get('impuestos', [VentasController::class, 'impuestos']);
        });

        Route::prefix('gastos')->group(function () {
            Route::get('impuestos', [GastosController::class, 'impuestos']);
        });

        Route::prefix('complementos')->group(function () {
            Route::get('pago/{factura}', [ComplementosController::class, 'obtenerComplementoPagos']);
            Route::get('nomina/{factura}', [ComplementosController::class, 'obtenerComplementoNomina']);
        });

    });

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('contafacil')->group(function() {
    Route::prefix('clientes/{clienteId}')->group(function () {
        Route::get('/', [ContafacilClienteController::class, 'index']);
        Route::post('/{fecha}/actualizar-regimenes', [
            ContafacilClienteController::class,
            'actualizarRegimenesFiscales'
        ]);
    });

    Route::prefix('ventas')->group(function() {
        Route::get('facturas', [VentasController::class, 'listadoFacturas']);
    });

    Route::prefix('gastos')->group(function() {
        Route::get('facturas', [GastosController::class, 'listadoFacturas']);
    });

    Route::prefix('balanza')->group(function () {
        Route::get('/balanza/{cliente}/{fecha}', [
            BalanzaComprobacionController::class, 'balanza'
        ]);
        Route::get('/impuestos/{cliente}', [
            BalanzaComprobacionController::class, 'impuestos'
        ]);
        Route::get('/polizas-ventas-gastos/{cliente}/{fecha}', [
            BalanzaComprobacionController::class, 'polizasAutomaticasGastosYVentas'
        ]);
        Route::get('/polizas-ventas-gastos/anual/{cliente}/{fecha}',[
            BalanzaComprobacionController::class, 'polizasAutomaticasGastosYVentasAnual'
        ]);
        Route::post('actualizar-saldos', [
            BalanzaComprobacionController::class, 'actualizarSaldosBalanza'
        ]);
        Route::get('calculo-iva/{clienteId}/{fecha}', [
            BalanzaComprobacionController::class, 'calculosDelImpuesto'
        ]);
        Route::get('columnas-deducciones/{clienteId}/{fecha}', [
            BalanzaComprobacionController::class, 'columnasDeducciones'
        ]);
        Route::get('determinacion-del-impuesto/{clienteId}/{fecha}', [
            BalanzaComprobacionController::class, 'determinacionDelImpuesto'
        ]);
        Route::post('actualizar-campos-editables/{clienteId}/{fecha}', [
            BalanzaComprobacionController::class, 'actualizarCamposEditables'
        ]);
    });

    Route::prefix('meses-trabajo/{cliente}')->group(function () {
        Route::get('verificar/{fecha}', [MesTrabajoController::class, 'verificarMesTrabajo']);
        Route::get('historico/{fecha}', [MesTrabajoController::class, 'obtenerHistorico']);
        Route::post('bloquear/{fecha}', [MesTrabajoController::class, 'bloquearMesTrabajo']);
        Route::post('desbloquear/{fecha}', [MesTrabajoController::class, 'desbloquearMesTrabajo']);
        Route::get('anio-trabajo/{fecha}', [MesTrabajoController::class, 'obtenerAnioTrabajo']);
    });

    Route::prefix('facturas-cliente')->group(function () {
        Route::post('/establecer-consideracion/{factura}', [FacturasClienteController::class, 'establecerConsideracion']);
        Route::post('/consideracion-multiple', [FacturasClienteController::class, 'establecerConsideracionMultiples']);
        Route::post('/establecer-fecha-pago/{factura}', [FacturasClienteController::class, 'establecerFechaPago']);
        Route::post('/establecer-concepto-sat/{factura}', [
            FacturasClienteController::class, 'establecerConceptoSat'
        ]);
        Route::post('/establecer-concepto-deduccion-personal/{factura}', [
            FacturasClienteController::class, 'establecerConceptoDeduccionPersonal'
        ]);
        Route::post('/establecer-deducible/{factura}', [
            FacturasClienteController::class, 'establecerDeducible'
        ]);
        Route::post('/establecer-tipo-ingreso/{factura}', [
            FacturasClienteController::class, 'establecerTipoIngreso'
        ]);
    });

    Route::prefix('facturas-numeros-cuentas/{clienteId}')->group(function () {
        Route::get('/poliza/{factura}', [
            FacturasNumeroCuentaController::class, 'obtenerPolizaAutomaticaFactura'
        ]);
        Route::post('/agregar-cuenta/{factura}', [
            FacturasNumeroCuentaController::class, 'agregarNumeroCuentaManual'
        ]);
        Route::delete('/eliminar-cuenta-manual/{factura}/{numeroCuenta}', [
            FacturasNumeroCuentaController::class, 'eliminarNumeroCuentaManual'
        ]);
    });

    Route::prefix('facturas')->group(function() {
        Route::post('/actualizar-montos/{factura}', [ContafacilFacturasController::class, 'actualizarMontos']);
        Route::post('/reestablecer-montos/{factura}', [ContafacilFacturasController::class, 'reestablecerOriginal']);
    });

    Route::prefix('numeros-cuentas')->group(function () {
        Route::get('listar', [NumerosCuentasController::class, 'numerosCuenta']);
        Route::post('crear', [NumerosCuentasController::class, 'crearNumeroCuenta']);
    });

    Route::prefix('bancos')->group(function() {
        Route::get('/', [BancosController::class, 'listarBancos']);
        Route::post('/crear', [BancosController::class, 'crearBanco']);
    });

    Route::prefix('bancos-proyectos')->group(function() {
        Route::get('/{cliente}', [BancosProyectosController::class, 'listar']);
        Route::post('/crear', [BancosProyectosController::class, 'crearProyecto']);
    });

    Route::prefix('comentarios')->group(function () {
        Route::prefix('facturas')->group(function () {
            Route::post('/agregar', [ComentariosController::class, 'agregarComentarioFactura']);
            Route::get('/{factura}', [ComentariosController::class, 'comentariosFactura']);
        });
    });

    Route::prefix('tablas-tarifas')->group(function () {
        Route::get('/configuracion', [TablasTarifasController::class, 'configuracionTablas']);
        Route::get('/tabla', [TablasTarifasController::class, 'obtenerTabla']);
        Route::post('/guardar-tabla', [TablasTarifasController::class, 'guardarTablaTarifa']);
    });

    Route::prefix('conceptos-sat')->group(function() {
        Route::get('/', [ConceptosSatController::class, 'obtenerConceptosSat']);
    });

    Route::prefix(('conceptos-deducciones-personales'))->group(function () {
        Route::get('/', [ConceptosDeduccionesPersonalesController::class, 'obtenerConceptosDeduccionesPersonales']);
    });

    Route::prefix('tipos-ingreso')->group(function () {
        Route::get('/', [TipoIngresoController::class, 'index']);
    });

    Route::prefix('saldos-a-favor')->group(function () {
        Route::get('catalogos', [SaldosAFavorController::class, 'catalogos']);
        Route::get('listar/{clienteId}/{fecha}', [SaldosAFavorController::class, 'index']);
        Route::post('crear/{clienteId}/{fecha}', [SaldosAFavorController::class, 'agregarSaldoAFavor']);
        Route::post('{saldoAFavor}/agregar-acreditamiento', [SaldosAFavorController::class, 'agregarAcreditamiento']);
        Route::delete('eliminar/{saldoAFavor}/{acreditamiento}', [
            SaldosAFavorController::class, 'eliminarAcreditamiento'
        ]);
        Route::delete('eliminar/{saldoAFavor}', [
            SaldosAFavorController::class, 'eliminarSaldoAFavor'
        ]);
    });

    Route::prefix('polizas-nominas')->group(function () {
        Route::get('/{clienteId}/{fecha}', [PolizasNominasController::class, 'polizasNomina']);
        Route::post('/{clienteId}/{fecha}/subir-archivo-excel', [PolizasNominasController::class, 'subirExcel']);
        Route::post('/{clienteId}/{fecha}/confirmar-datos-excel', [PolizasNominasController::class, 'confirmarDatosExcel']);
    });

    Route::prefix('campos-editables')->group(function () {
        Route::post('actualizar-campo/{clienteId}/{fecha}', [
            CamposEditablesController::class, 'actualizarCampoEditable'
        ]);
    });
});
