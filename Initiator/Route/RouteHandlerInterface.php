<?php


namespace Nomess\Initiator\Route;


use Nomess\Exception\NotFoundException;

/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
interface RouteHandlerInterface
{
    
    public const ROUTE           = 'route';
    public const NAME            = 'name';
    public const CONTROLLER      = 'controller';
    public const METHOD          = 'method';
    public const REQUIREMENTS    = 'requirements';
    public const REQUEST_METHODS = 'request_methods';
    public const HAS_PARAMETERS  = 'has_parameters';
    
    
    /**
     * @param string $routeName
     * @param array $parameters
     * @return string|null
     * @throws NotFoundException
     */
    public function getUri( string $routeName, array $parameters ): ?string;
}
