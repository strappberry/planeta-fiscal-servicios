<?php

namespace App\Contafacil\Compartido\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Reflection;
use ReflectionClass;
use ReflectionMethod;

abstract class ViewModel implements Arrayable
{
    /**
     * Funciones que no se deben incluir en el array final.
     *
     * @var array
     */
    protected $excepciones = [];

    /**
     * Convertir las funciones publicas de la clase en un array.
     */
    public function toArray(): array
    {
        return collect((new ReflectionClass($this))->getMethods())
            ->reject(
                fn (ReflectionMethod $method) => in_array($method->getName(), ['__construct', 'toArray'])
            )
            ->reject(
                fn (ReflectionMethod $method) => in_array($method->getName(), $this->excepciones)
            )
            ->filter(
                fn (ReflectionMethod $method) => in_array(
                    'public', Reflection::getModifierNames($method->getModifiers())
                )
            )
            ->mapWithKeys(fn (ReflectionMethod $method) => [
                (string) str($method->getName())->snake() => $this->{$method->getName()}()
            ])
            ->toArray()
            ;
    }
}
