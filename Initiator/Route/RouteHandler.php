<?php


namespace Nomess\Initiator\Route;


use App\Package\Administer\Report\Dispatcher;
use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Container\Container;
use Nomess\Exception\NotFoundException;

/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
class RouteHandler implements RouteHandlerInterface
{
    private const CACHE_ROUTE_NAME = 'routes';
    private CacheHandlerInterface $cacheHandler;
    
    public function __construct( CacheHandlerInterface $cacheHandler )
    {
        $this->cacheHandler = $cacheHandler;
    }
    
    
    public function getUri( string $routeName, array $parameters ): ?string
    {
        $routes = NULL;
        
        if( ( $routes = $this->cacheHandler->get( self::CACHE_ROUTE_NAME, 'routes_match' ) ) === NULL ) {
            $routes = Container::getInstance()->get( RouteBuilder::class )->build();
        }
        
        foreach( $routes as $name => $route ) {
            if( $routeName === $name ) {
                $uri = [];
                
                if( $route[RouteHandlerInterface::HAS_PARAMETERS] ) {
                    foreach( explode( '/', $route[RouteHandlerInterface::ROUTE] ) as $section ) {
                        
                        if( strpos( $section, '{' ) !== FALSE ) {
                            preg_match( '/.*\{(.+)\}.*/', $section, $param );
                            
                            if( !array_key_exists( $param[1], $parameters ) ) {
                                throw new NotFoundException( 'The parameter "' . $param[1] . '" is required in your route "' . $routeName . '"' );;
                            }
                            
                            $section = $parameters[$param[1]];
                            unset( $parameters[$param[1]] );
                        }
                        
                        $uri[] = $section;
                    }
                    
                    $uri = implode( '/', $uri);
                }else{
                    $uri = $route[RouteHandlerInterface::ROUTE];
                }
    
    
                $i = 0;
                foreach( $parameters as $parameter => $value ) {
                    
                    if( $i === 0 ) {
                        $uri .= "?$parameter=$value";
                    } else {
                        $uri .= "&$parameter=$value";
                    }
                    
                    if( $i === 0 ) {
                        $i++;
                    }
                }
                
                return $uri;
            }
        }
        
        return NULL;
    }
}
