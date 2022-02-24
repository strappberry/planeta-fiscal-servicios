<?php
namespace App\Reportes;

interface Reporte {

    /**
     * Nombre con el que se guardara el archivo
     */
    public function nombreArchivo() : string;

    /**
     * Encabezados de las tablas del reporte
     */
    public function encabezados(): array;

    /**
     * Paginas con los datos del reporte
     */
    public function paginas() : array;

}