<?php

namespace Nomess\Tools\Twig;

use Nomess\Container\Container;
use Nomess\Exception\NotFoundException;
use Nomess\Initiator\Route\RouteHandlerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PathExtension extends AbstractExtension
{
    
    public function getFunctions()
    {
        return [
            new TwigFunction( 'path', [ $this, 'path' ] ),
        ];
    }
    
    
    public function path( string $routeName, array $param = [] )
    {
        /** @var RouteHandlerInterface $routeHandler */
        $routeHandler = Container::getInstance()->get( RouteHandlerInterface::class );
        
        if( NOMESS_CONTEXT === 'DEV' ) {
            return $routeHandler->getUri( $routeName, $param );
        }
        
        try {
            return $routeHandler->getUri( $routeName, $param );
        } catch( NotFoundException $exception ) {
            return '#';
        }
    }
}
