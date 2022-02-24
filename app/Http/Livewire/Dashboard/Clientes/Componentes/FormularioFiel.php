<?php

namespace App\Http\Livewire\Dashboard\Clientes\Componentes;

use App\Models\ClaveSat;
use Exception;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpCfdi\Credentials\Credential;

class FormularioFiel extends Component
{
    use WithFileUploads;

    public $cliente;
    public $formulario = [
        'cer' => '',
        'key' => '',
        'password' => '',
        'caducidad' => '',
        'tipo' => 'fiel',
        'activo' => true,
    ];
    public $cer;
    public $key;
    public $mensajeError = '';

    protected function rules()
    {
        $validaciones = [
            'cer' => 'required',
            'key' => 'required',
        ];

        return array_merge(
            ClaveSat::LIVEWIRE_RULES,
            $validaciones
        );
    }

    public function getUltimaFielProperty()
    {
        return $this->cliente->clavesSat()->esFiel()->latest()->first();
    }

    public function guardarFiel()
    {
        $this->validate();
        $this->mensajeError = "";

        try {
            $fiel = Credential::create(
                File::get($this->cer->getRealPath()),
                File::get($this->key->getRealPath()),
                $this->formulario['password'],
            );

            if (! $fiel->isFiel()) {
                $this->mensajeError = "El archivo no es un FIEL";
                return;
            }

            if (! $fiel->certificate()->validOn()) {
                $this->mensajeError = "El archivo no es válido a la fecha actual";
                return;
            }

            $this->formulario['cer'] = $this->cer->store(
                'archivos/' . $this->cliente->rfc . '/fiel',
            );
            $this->formulario['key'] = $this->key->store(
                'archivos/' . $this->cliente->rfc . '/fiel',
            );
            $this->formulario['caducidad'] = $fiel->certificate()->validToDateTime()->format('Y-m-d H:i:s');

            $this->cliente->clavesSat()->create($this->formulario);
        } catch (Exception $e) {
            $this->mensajeError = 'Contraseña incorrecta';
        }
    }

    public function render()
    {
        return view('livewire.dashboard.clientes.componentes.formulario-fiel');
    }
}
