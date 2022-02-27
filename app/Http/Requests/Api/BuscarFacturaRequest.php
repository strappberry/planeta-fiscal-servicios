<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BuscarFacturaRequest extends FormRequest
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
            'rfc' => 'required|string|max:13',
            'tipo_busqueda' => 'required|in:emitido,recibido',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ];
    }
}
