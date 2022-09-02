<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Kontafacil
    |--------------------------------------------------------------------------
    |
    | Url del servicio de Kontafacil.
    |
    */
    'kontafacil_url' => env('KONTAFACIL_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Token API de Kontafacil
    |--------------------------------------------------------------------------
    |
    | Este token es necesario para acceder a la API de Planeta Fiscal.
    | Esta configurado en la configuración de Kontafacil.
    |
    */
    'kontafacil_token_remoto' => env('KONTAFACIL_TOKEN_REMOTO', ''),

];
