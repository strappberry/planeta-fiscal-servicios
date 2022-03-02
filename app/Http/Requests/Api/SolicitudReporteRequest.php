<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SolicitudReporteRequest
 *
 * @property-read stirng $tipo
 * @property-read stirng $rfc
 * @property-read stirng $fecha_inicio
 * @property-read stirng $fecha_fin
 */
class SolicitudReporteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo' => 'required|in:simplificado',
            'rfc' => 'required|string|max:13',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ];
    }
}
