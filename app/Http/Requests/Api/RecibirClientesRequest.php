<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RecibirClientesRequest extends FormRequest
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
     * Los campos cer y key se obtienen como url hacia el archivo
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clientes'                  => 'required|array',
            'clientes.*.razon_social'   => 'required',
            'clientes.*.rfc'            => 'required',
            'clientes.*.regimen_fiscal' => 'required',
            'clientes.*.cer'            => 'required',
            'clientes.*.key'            => 'required',
            'clientes.*.password'       => 'required',
        ];
    }
}
