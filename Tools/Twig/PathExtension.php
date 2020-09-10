<?php

namespace Nomess\Tools\Twig;

use InvalidArgumentException;
use Nomess\Component\Cache\CacheHandler;
use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Container\Container;
use Nomess\Exception\NotFoundException;
use Nomess\Initiator\Route\RouteBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PathExtension extends AbstractExtension
{
    
    private const CACHE_NAME = 'routes';
    
    
    public function getFunctions()
    {
        return [
            new TwigFunction( 'path', [ $this, 'path' ] ),
        ];
    }
    
    
    public function path( string $routeName, array $param = NULL )
    {
        $routes = $this->getCache();
        
        foreach( $routes as $key => $route ) {
            if( $route['name'] === $routeName ) {
                
                if( strpos( $key, '{' ) !== FALSE ) {
                    $sections = explode( '/', $key );
                    
                    foreach( $sections as &$section ) {
                        
                        if( strpos( $section, '{' ) !== FALSE ) {
                            $purgedSection = str_replace( [ '{', '}' ], '', $section );
                            
                            if( !empty( $param ) && array_key_exists( $purgedSection, $param ) ) {
                                $section = $param[$purgedSection];
                                
                                if( empty( $section ) ) {
                                    if( NOMESS_CONTEXT === 'DEV' ) {
                                        throw new \Exception( 'Your parameter "' . $purgedSection . '" for route ' . $routeName . ' is void' );
                                    }
                                    
                                    return '#';
                                }
                                unset( $param[$purgedSection] );
                            } else {
                                if( NOMESS_CONTEXT === 'DEV' ) {
                                    throw new InvalidArgumentException( 'Missing an dynamic data in your url' );
                                }
                                
                                return '#';
                            }
                        }
                    }
                    
                    $key = implode( '/', $sections );
                }
                
                if( strpos( $key, '{' ) ) {
                    if( NOMESS_CONTEXT === 'DEV' ) {
                        throw new InvalidArgumentException( 'Missing an dynamic data in your url' );
                    }
                    
                    return '#';
                }
                
                if( !empty( $param ) ) {
                    $i = 0;
                    
                    foreach( $param as $index => $value ) {
                        
                        if( $i === 0 ) {
                            $key .= "?$index=$value";
                            $i++;
                        } else {
                            $key .= "&$index=$value";
                        }
                    }
                }
                
                return $key;
            }
        }
        
        if( NOMESS_CONTEXT === 'DEV' ) {
            throw new NotFoundException( "Your route $routeName has not found" );
        }
        
        return '#';
    }
    
    
    private function getCache(): array
    {
        /** @var CacheHandlerInterface $cacheHandler */
        $cacheHandler = Container::getInstance()->get( CacheHandler::class );
        
        if( ( $route = $cacheHandler->get( self::CACHE_NAME, 'routes_match' ) ) === NULL ) {
            return Container::getInstance()->get( RouteBuilder::class )->build();
        }
        
        
        return $route;
    }
}
