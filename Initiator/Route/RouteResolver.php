<?php

namespace Nomess\Initiator\Route;

use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Internal\Scanner;

class RouteResolver
{
    
    use Scanner;
    
    private const CACHE_NAME = 'route';
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
        
        if( $routes === NULL ) {
            $routes = $this->routeBuilder->build();
            $this->cacheHandler->add( self::CACHE_NAME, [
                'value' => $routes
            ] );
        }
        
        
        foreach( $routes as $key => $route ) {
            
            
            if( $key === '/' . $_GET['p'] ) {
                return $route;
            }
            
            $arrayRoute = explode( '/', $key );
            $arrayUrl   = explode( '/', $_GET['p'] );
            
            unset( $arrayRoute[0] );
            
            $success = TRUE;
            $i       = 0;
            
            foreach( $arrayRoute as $key => $section ) {
                if( !empty( $section ) ) {
                    if( strpos( $section, '{' ) === FALSE ) {
                        
                        if( isset( $arrayUrl[$i] ) ) {
                            if( strpos( $arrayUrl[$i], '?' ) !== FALSE ) {
                                $arrayUrl[$i] = explode( '?', $arrayUrl[$i] )[0];
                            }
                            
                            if( strpos( $arrayUrl[$i], '&' ) !== FALSE ) {
                                $arrayUrl[$i] = explode( '&', $arrayUrl[$i] )[0];
                            }
                            
                            if( ( isset( $arrayUrl[$i] ) && $section !== $arrayUrl[$i] )
                                || !isset( $arrayUrl[$i] ) ) {
                                
                                $success = FALSE;
                                break 1;
                            }
                        } else {
                            $success = FALSE;
                            break 1;
                        }
                    } else {
                        if( empty( $arrayUrl[$i] ) ) {
                            $success = FALSE;
                            break 1;
                        }
                        
                        $sectionPurged = $this->getIdSection( $section );
                        
                        if( isset( $route['requirements'][$sectionPurged] ) ) {
                            if( !preg_match( '/' . $route['requirements'][$sectionPurged] . '/', $arrayUrl[$i] ) ) {
                                $success = FALSE;
                                break 1;
                            }
                        }
                        
                        $_GET[$sectionPurged] = $arrayUrl[$i];
                    }
                    
                    unset( $arrayUrl[$i] );
                    $i++;
                } else {
                    $success = FALSE;
                    break 1;
                }
            }
            
            if( $success === TRUE && empty( $arrayUrl ) ) {
                return $route;
            }
        }
        
        return NULL;
    }
    
    
    private function getIdSection( string $section ): string
    {
        return str_replace( [ '{', '}' ], '', $section );
    }
}
