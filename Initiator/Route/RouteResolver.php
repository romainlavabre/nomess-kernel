<?php

namespace Nomess\Initiator\Route;

use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Internal\Scanner;

class RouteResolver
{
    
    use Scanner;
    
    private const CACHE_NAME = 'routes';
    private CacheHandlerInterface $cacheHandler;
    private RouteBuilder          $routeBuilder;
    
    
    public function __construct(
        CacheHandlerInterface $cacheHandler,
        RouteBuilder $routeBuilder
    )
    {
        $this->cacheHandler = $cacheHandler;
        $this->routeBuilder = $routeBuilder;
    }
    
    
    public function resolve(): ?array
    {
        $routes = $this->cacheHandler->get( self::CACHE_NAME, 'routes_match' );
        if( empty( $routes ) ) {
            $routes = $this->routeBuilder->build();
            $this->cacheHandler->add( self::CACHE_NAME, [
                'value' => $routes
            ] );
        }
        
        foreach( $routes as $routeName => $route ) {
            
            if( !in_array( $_SERVER['REQUEST_METHOD'], is_array( $route[RouteHandlerInterface::REQUEST_METHODS] ) ? $route[RouteHandlerInterface::REQUEST_METHODS] : [], TRUE )
                && is_array( $route[RouteHandlerInterface::REQUEST_METHODS] ) ) {
                
                continue;
            }
            
            if( !$route[RouteHandlerInterface::HAS_PARAMETERS] ) {
                
                if( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) === $route[RouteHandlerInterface::ROUTE] ) {
                    return $route;
                }
                
                continue;
            }
            
            $arrayRoute = explode( '/', $route[RouteHandlerInterface::ROUTE] );
            $arrayUri   = explode( '/', parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
            
            unset( $arrayRoute[0], $arrayUri[0] );
            
            if( count( $arrayRoute ) !== count( $arrayUri ) ) {
                continue;
            }
            
            $match = TRUE;
            
            foreach( $arrayRoute as $key => $section ) {
                
                if( empty( $arrayUri[$key] ) || ( $section !== $arrayUri[$key] && strpos( $section, '{' ) === FALSE ) ) {
                    $match = FALSE;
                    break 1;
                }
                
                if( strpos( $section, '{' ) === FALSE ) {
                    continue;
                }
                
                $param = str_replace( [ '{', '}' ], '', $section );
                
                if( isset( $route[RouteHandlerInterface::REQUIREMENTS][$param] ) ) {
                    if( !preg_match( '/' . $route[RouteHandlerInterface::REQUIREMENTS][$param] . '/', $arrayUri[$key] ) ) {
                        $match = FALSE;
                        break 1;
                    }
                }
                
                $_GET[$param] = $arrayUri[$key];
            }
            
            if( $match ) {
                return $route;
            }
        }
        
        return NULL;
    }
}
