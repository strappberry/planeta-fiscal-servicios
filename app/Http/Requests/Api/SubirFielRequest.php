<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SubirFielRequest extends FormRequest
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
            'password' => 'required|string',
            'regimen_fiscal' => 'required|string',
            'archivo_cer' => 'required|string',
            'archivo_key' => 'required|string',
        ];
    }
}
