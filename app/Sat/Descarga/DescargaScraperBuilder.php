<?php
namespace App\Sat\Descarga;

use App\Models\Cliente;
use Carbon\Carbon;

interface DescargaScraperBuilder {

    public function __construct(Cliente $cliente);

    /**
     * Establecer fecha de inicio de descarga
     * @param \Carbon\Carbon $fecha
     * @return self
     */
    public function fechaInicial(Carbon $fecha) : self;

    /**
     * Establecer fecha de fin de descarga
     * @param \Carbon\Carbon $fecha
     * @return self
     */
    public function fechaFinal(Carbon $fecha) : self;

    /**
     * Establecer carpeta de descarga
     * 
     * @param string $carpeta
     * @return self
     */
    public function carpeta(string $carpeta) : self;

    /**
     * Comenzar descarga de facturas
     * Se descargaran automaticamente los archivos emitidos y recibidos
     * 
     * @return void
     */
    public function comenzar();

}