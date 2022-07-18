<?php

namespace App\Contafacil\Compartido\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Reflection;
use ReflectionClass;
use ReflectionMethod;

abstract class ViewModel implements Arrayable
{
    public function toArray()
    {
        return collect((new ReflectionClass($this))->getMethods())
            ->reject(
                fn (ReflectionMethod $method) => in_array($method->getName(), ['__construct', 'toArray'])
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
