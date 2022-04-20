<?php

use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\FacturasController;
use App\Http\Controllers\ArchivosController;
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
    });

    Route::prefix('clientes')->group(function () {
        Route::post('/recibir-clientes', [ClientesController::class, 'recibirClientes']);
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

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

