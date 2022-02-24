<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLwSelect extends Component
{
    public $etiqueta;
    public $identificador;
    public $modelo;
    public $ayuda;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($etiqueta, $identificador, $modelo)
    {
        $this->etiqueta      = $etiqueta;
        $this->identificador = $identificador;
        $this->modelo        = $modelo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.app-lw-select');
    }
}
