<?php

use App\Http\Controllers\Dashboard\ClientesController;
use App\Http\Controllers\Dashboard\FacturasClienteController;
use App\Http\Controllers\ReportesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false,
]);

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')
->prefix('admin')
->as('admin.')
->group(function () {

    Route::prefix('clientes')
    ->as('clientes.')
    ->group(function () {
        Route::get('/', [ClientesController::class, 'index'])->name('index');
        Route::get('/crear', [ClientesController::class, 'crear'])->name('crear');
        Route::get('/configuracion/{cliente}', [ClientesController::class, 'configuracion'])->name('configuracion');

        Route::prefix('{cliente}/facturas')
        ->as('facturas.')
        ->group(function () {
            Route::get('/', [FacturasClienteController::class, 'index'])->name('index');
            Route::get('/descargar', [FacturasClienteController::class, 'descargarFacturas'])->name('descargarFacturas');
        });
    });

});

Route::prefix('reportes')->as('reportes.')->group(function() {
    Route::get(
        'atender-solicitud-reporte/{token}',
        [ReportesController::class, 'atenderSolicitudReporte']
    )->name('atender-solicitud-reporte');
    // Route::get(
    //     '/simplificado/{rfc}/{fechaInicio}/{fechaFin}',
    //     [App\Http\Controllers\ReportesController::class, 'simplificado']
    // )->name('reporte.simplificado');
});