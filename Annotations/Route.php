<?php


namespace Nomess\Annotations;

/**
 * @Annotation
 */
class Route
{
    
    private string $name;
    private array  $methods;
    private array  $requirements;
}
