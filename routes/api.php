<?php

use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\FacturasController;
use App\Http\Controllers\Api\SolicitudesFacturaController;
use App\Http\Controllers\ArchivosController;
use App\Http\Controllers\Contafacil\ComentariosController;
use App\Http\Controllers\Contafacil\ComplementosController;
use App\Http\Controllers\Contafacil\GastosController;
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
            Route::get('facturas', [VentasController::class, 'listadoFacturas']);
        });

        Route::prefix('gastos')->group(function () {
            Route::get('impuestos', [GastosController::class, 'impuestos']);
            Route::get('facturas', [GastosController::class, 'listadoFacturas']);
        });

        Route::prefix('comentarios')->group(function () {
            Route::prefix('facturas')->group(function () {
                Route::post('/agregar', [ComentariosController::class, 'agregarComentarioFactura']);
                Route::get('/{factura}', [ComentariosController::class, 'comentariosFactura']);
            });
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

