<?php

namespace Nomess\Initiator\Route;

use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Component\Parser\AnnotationParserInterface;
use Nomess\Exception\ConflictException;
use Nomess\Exception\MissingConfigurationException;
use Nomess\Installer\InstallerHandlerInterface;
use Nomess\Internal\Scanner;

/**
 * This class build the routes of application
 *
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
class RouteBuilder
{
    
    use Scanner;
    
    private ConfigStoreInterface      $configStore;
    private InstallerHandlerInterface $installerHandler;
    private AnnotationParserInterface $annotationParser;
    private array                     $routes;
    
    
    public function __construct(
        ConfigStoreInterface $configStore,
        InstallerHandlerInterface $installerHandler,
        AnnotationParserInterface $annotationParser )
    {
        $this->configStore      = $configStore;
        $this->installerHandler = $installerHandler;
        $this->annotationParser = $annotationParser;
    }
    
    
    /**
     * General methods, scan the target directory (recurive) and call the other configurations
     *
     * @return array
     * @throws MissingConfigurationException
     * @throws \Nomess\Component\Config\Exception\ConfigurationNotFoundException
     * @throws \ReflectionException
     */
    public function build(): array
    {
        $this->routes = array();
        $tree         = $this->scanRecursive(
            $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['path']['default_controller']
        );
        
        foreach( $tree as $directory ) {
            
            $content = scandir( $directory );
            
            foreach( $content as $file ) {
                if( strpos( $file, '.php' ) !== FALSE ) {
                    
                    $reflectionClass = new \ReflectionClass( $this->getNamespace( $directory . $file ) );
                    
                    $this->getAnnotations( $reflectionClass->getMethods(), $this->getHeader( $reflectionClass ) );
                }
            }
        }
        
        foreach( $this->installerHandler->getPackages() as $nomessInstaller ) {
            foreach( $nomessInstaller->controller() as $classname ) {
                $reflectionClass = new \ReflectionClass( $classname );
                
                $this->getAnnotations( $reflectionClass->getMethods(), $this->getHeader( $reflectionClass ) );
            }
        }
        
        return $this->routes;
    }
    
    
    /**
     * Return the base of route
     *
     * @param \ReflectionClass $reflectionClass
     * @return string|null
     * @throws MissingConfigurationException
     */
    private function getHeader( \ReflectionClass $reflectionClass ): ?string
    {
        
        if( $this->annotationParser->has( 'Route', $reflectionClass ) ) {
            $value = $this->annotationParser->getValue( 'Route', $reflectionClass );
            
            if( array_key_exists( 0, $value ) ) {
                return $value[0];
            }
            
            throw new MissingConfigurationException( 'You have a invalid configuration for class header in "' . $reflectionClass->getName() . '"' );
        }
        
        return NULL;
    }
    
    
    /**
     * @param \ReflectionMethod[]|null $reflectionMethods
     */
    private function getAnnotations( ?array $reflectionMethods, ?string $header ): void
    {
        if( !empty( $reflectionMethods ) ) {
            foreach( $reflectionMethods as $reflectionMethod ) {
                
                if( $this->annotationParser->has( 'Route', $reflectionMethod ) ) {
                    $value        = $this->annotationParser->getValue( 'Route', $reflectionMethod );
                    $route        = ( array_key_exists( 0, $value ) ) ? $value[0] : ( array_key_exists( 'path', $value ) ? $value['path'] : NULL );
                    $methods      = $value['methods'] ?? NULL;
                    $requirements = $value['requirements'] ?? NULL;
                    $name         = ( array_key_exists( 'name', $value ) ) ? $value['name'] : $this->generateName( $route, $methods );
                    
                    if( $route === NULL ) {
                        throw new MissingConfigurationException( 'The path for method "' . $reflectionMethod->getName() . '" in "' .
                                                                 $reflectionMethod->getDeclaringClass()->getName() . '" was not found' );
                    }
                    
                    $route = $header . $route;
                    $this->isUniqueRoute( $route, $methods, $reflectionMethod->getDeclaringClass() );
                    $this->isUniqueName( $name );
                    
                    $this->routes[$name] = [
                        RouteHandlerInterface::ROUTE           => $route,
                        RouteHandlerInterface::NAME            => $name,
                        RouteHandlerInterface::REQUEST_METHODS => $methods,
                        RouteHandlerInterface::METHOD          => $reflectionMethod->getName(),
                        RouteHandlerInterface::CONTROLLER      => $reflectionMethod->getDeclaringClass()->getName(),
                        RouteHandlerInterface::REQUIREMENTS    => $requirements,
                        RouteHandlerInterface::HAS_PARAMETERS  => preg_match( '/\{.+\}/', $route )
                    ];
                }
            }
        }
    }
    
    
    private function generateName( string $route, ?array $methods ): string
    {
        if( empty( $methods ) ) {
            return $route . '_ALLMETHODS';
        }
        
        foreach( $methods as $method ) {
            $route .= '_' . $methods;
        }
        
        return $route;
    }
    
    
    private function getNamespace( string $filename ): string
    {
        $lines = file( $filename );
        
        foreach( $lines as $line ) {
            if( preg_match( '/^namespace *([a-zA-Z0-9\\\_-]+);.*/', $line, $output ) ) {
                $shortName = explode( '/', $filename );
                
                return $output[1] . '\\' . str_replace( '.php', '', $shortName[count( $shortName ) - 1] );
            }
        }
        
        throw new \ParseError( 'Impossible to resolve the namespace of file "' . $filename . '"' );
    }
    
    
    private function isUniqueRoute( string $route, ?array $methods, \ReflectionClass $reflectionClass ): void
    {
        
        foreach( $this->routes as $array ) {
            if( $array[RouteHandlerInterface::ROUTE] === $route ) {
                
                if( !empty( $methods ) ) {
                    $found = FALSE;
                    
                    foreach( $array[RouteHandlerInterface::REQUEST_METHODS] as $method ) {
                        if( in_array( $method, $methods, TRUE ) ) {
                            $found = TRUE;
                            
                            break;
                        }
                    }
                    
                    if( !$found ) {
                        return;
                    }
                }
                
                throw new ConflictException( 'Your route "' . $route . '" declared in "' . $reflectionClass->getName() . '::class" is already used by ' .
                                             $array[RouteHandlerInterface::CONTROLLER] . ' for method ' . $array[RouteHandlerInterface::METHOD] );
            }
        }
    }
    
    
    private function isUniqueName( string $name ): void
    {
        if( array_key_exists( $name, $this->routes ) ) {
            throw new ConflictException( 'Your route with name "' . $name . '" is already used' );
        }
    }
}
