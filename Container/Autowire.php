<?php


namespace Nomess\Container;


use Nomess\Component\Cache\Builder\CacheBuilder;
use Nomess\Component\Cache\CacheHandler;
use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Component\Config\ConfigHandler;
use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Component\Parser\YamlParser;
use Nomess\Event\EventListener;
use Nomess\Event\EventListenerInterface;
use Nomess\Event\EventSubscriber;
use Nomess\Event\EventSubscriberInterface;
use Nomess\Exception\ConflictException;
use Nomess\Exception\MissingConfigurationException;
use Nomess\Exception\NotFoundException;
use Nomess\Initiator\Route\RouteHandler;
use Nomess\Initiator\Route\RouteHandlerInterface;
use Nomess\Installer\InstallerHandler;
use Nomess\Installer\InstallerHandlerInterface;
use ReflectionMethod;

class Autowire
{
    
    private const CACHE_NAME = 'container';
    private array $instance      = [];
    private array $configuration = [];
    
    
    public function __construct( Container $container )
    {
        $this->instance[Container::class]     = $container;
        $this->instance[ConfigHandler::class] = new ConfigHandler();
        $this->instance[YamlParser::class]    = new YamlParser();
        $this->instance[CacheBuilder::class]  = new CacheBuilder( $this->instance[ConfigHandler::class], $this->instance[Container::class] );
        $this->instance[CacheHandler::class]  = new CacheHandler(
            $this->instance[ConfigHandler::class],
            $this->instance[Container::class],
            $this->instance[CacheBuilder::class]
        );
        $this->initConfig( $this->instance[ConfigHandler::class] );
    }
    
    
    public function get( string $classname )
    {
    
        if( array_key_exists( $classname, $this->instance ) ) {
            return $this->instance[$classname];
        }
    
        if( array_key_exists( $classname, $this->configuration )
            && ( is_string( $this->configuration[$classname] ) || is_int( $this->configuration[$classname] ) )
            && array_key_exists( $this->configuration[$classname], $this->instance ) ) {
            return $this->instance[$this->configuration[$classname]];
        }
        
        return $this->make( $classname, FALSE );
    }
    
    
    public function getByReflectionParameter( \ReflectionParameter $reflectionParameter )
    {
        if( array_key_exists( $classname = $reflectionParameter->getType()->getName(), $this->configuration ) ) {
            if( is_array( $this->configuration[$classname] ) ) {
                if( array_key_exists( $reflectionParameter->getName(), $this->configuration[$classname] ) ) {
                    return $this->get( $this->configuration[$classname][$reflectionParameter->getName()] );
                }
                
                $result = [];
                
                foreach( $this->configuration[$classname] as $class ) {
                    $result[] = $this->get( $class );
                }
                
                return $result;
            }
            
            return $this->get( $this->configuration[$classname] );
        }
        
        return $this->get( $classname );
    }
    
    
    /**
     * @param string $classname
     * @return mixed
     * @throws MissingConfigurationException
     * @throws \ReflectionException
     */
    public function make( string $classname, bool $force = TRUE )
    {
        
        $reflectionClass = new \ReflectionClass( $classname );
        
        if( !$reflectionClass->isInstantiable() ) {
            if( array_key_exists( $classname, $this->configuration ) ) {
                $reflectionClass = new \ReflectionClass( $this->configuration[$classname] );
                $classname       = $reflectionClass->getName();
            } else {
                throw new MissingConfigurationException( "Impossible of autowire the class $classname, she's not instanciable" );
            }
        }
        
        if( $reflectionClass->getConstructor() !== NULL ) {
            $this->constructorResolver( $reflectionClass->getConstructor()->getParameters(), $reflectionClass, $force );
        } else {
            $this->constructorResolver( NULL, $reflectionClass, $force );
        }
        
        $this->propertyResolver( $reflectionClass->getProperties(), $this->instance[$classname] );
        $this->methodsResolver( $reflectionClass->getMethods(), $this->instance[$classname] );
        
        return $this->instance[$classname];
    }
    
    
    /**
     * @param \ReflectionParameter[]|null $reflectionParameters
     * @param \ReflectionClass $reflectionClass
     * @return void
     */
    private function constructorResolver( ?array $reflectionParameters, \ReflectionClass $reflectionClass, bool $force = FALSE ): void
    {
        $parameters = array();
        
        if( !empty( $reflectionParameters ) ) {
            foreach( $reflectionParameters as $reflectionParameter ) {
                $parameters[] = $this->getGroup( $reflectionParameter->getType()->getName(), $reflectionParameter->getName(), $reflectionClass->getConstructor() );
            }
        }
        
        if( !array_key_exists( $reflectionClass->getName(), $this->instance ) || $force ) {
            $this->instance[$reflectionClass->getName()] = $reflectionClass->newInstanceArgs( $parameters );
        }
    }
    
    
    /**
     * @param ReflectionMethod[]|null $reflectionMethods
     * @param object $object
     */
    private function methodsResolver( ?array $reflectionMethods, object $object ): void
    {
        foreach( $reflectionMethods as $reflectionMethod ) {
            
            if( $this->hasAnnotation( $reflectionMethod ) ) {
                $parameters = [];
                
                $reflectionParameters = $reflectionMethod->getParameters();
                
                if( !empty( $reflectionParameters ) ) {
                    foreach( $reflectionParameters as $reflectionParameter ) {
                        $parameters[] = $this->getGroup( $reflectionParameter->getType()->getName(), $reflectionParameter->getName(), $reflectionMethod );
                    }
                }
                
                $reflectionMethod->invokeArgs( $object, $parameters );
            }
        }
    }
    
    
    /**
     * @param \ReflectionProperty[]|null $reflectionProperties
     * @param object $object
     */
    private function propertyResolver( ?array $reflectionProperties, object $object ): void
    {
        if( !empty( $reflectionProperties ) ) {
            foreach( $reflectionProperties as $reflectionProperty ) {
                if( $this->hasAnnotation( $reflectionProperty ) ) {
                    
                    if( !$reflectionProperty->isPublic() ) {
                        $reflectionProperty->setAccessible( TRUE );
                    }
                    
                    $reflectionProperty->setValue(
                        $object,
                        $this->getGroup( $reflectionProperty->getType()->getName(), $reflectionProperty->getName(), $reflectionProperty )
                    );
                }
            }
        }
    }
    
    
    private function getGroup( string $type, string $paramName, $reflection )
    {
        $list = array();
        
        if( $type === 'array' ) {
            
            if( $reflection instanceof ReflectionMethod ) {
                $type = $this->getTypeAnnotationParameter( $paramName, $reflection );
            } else {
                $type = $this->getTypeAnnotationProperty( $paramName, $reflection );
            }
        }
        
        if( isset( $this->configuration[$type] ) ) {
            
            if( is_array( $this->configuration[$type] ) ) {
                if( array_key_exists( $paramName, $this->configuration[$type] ) ) {
                    return $this->get( $this->configuration[$type][$paramName] );
                }
                
                // If is not array, send and array of parameter
                foreach( $this->configuration[$type] as $class ) {
                    $list[] = $this->get( $class );
                }
            } else {
                return $this->get( $this->configuration[$type] );
            }
        } else {
            return $this->get( $type );
        }
        
        return $list;
    }
    
    
    private function hasAnnotation( \Reflector $reflector ): bool
    {
        if( strpos( $reflector->getDocComment(), '@Inject' ) !== FALSE ) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    
    /**
     * @param string $paramName
     * @param ReflectionMethod $reflectionMethod
     * @return string
     * @throws NotFoundException
     */
    private function getTypeAnnotationParameter( string $paramName, ReflectionMethod $reflectionMethod ): string
    {
        preg_match( '/@param ([A-Za-z0-1_\\\]+)\[?\]?[|null]* \$' . $paramName . '/', $reflectionMethod->getDocComment(), $output );
        
        if( isset( $output[1] ) ) {
            if( class_exists( $output[1] ) ) {
                return $output[1];
            }
            
            return $this->criticalClassResolver( $output[1], $reflectionMethod->getDeclaringClass() );
        }
        
        throw new \InvalidArgumentException( "The argument $$paramName in " . $reflectionMethod->getDeclaringClass() . ' for method ' . $reflectionMethod->getName() . ' has unresolved' );
    }
    
    
    private function getTypeAnnotationProperty( string $paramName, \ReflectionProperty $reflectionProperty ): string
    {
        preg_match( '/@var ([A-Za-z0-1_\\\]+)\[?\]?[|null]*/', $reflectionProperty->getDocComment(), $output );
        
        if( isset( $output[1] ) ) {
            if( class_exists( $output[1] ) ) {
                return $output[1];
            }
            
            return $this->criticalClassResolver( $output[1], $reflectionProperty->getDeclaringClass() );
        }
        
        throw new \InvalidArgumentException( "The argument $$paramName in " . $reflectionProperty->getDeclaringClass()->getName() . ' has unresolved' );
    }
    
    
    private function criticalClassResolver( string $classname, \ReflectionClass $reflectionClass ): ?string
    {
        //Search for class in used namespace
        $file  = file( $reflectionClass->getFileName() );
        $found = array();
        
        foreach( $file as $line ) {
            if( strpos( $line, $classname ) !== FALSE && strpos( $line, 'use' ) !== FALSE ) {
                
                preg_match( '/ +[A-Za-z0-9_\\\]*/', $line, $output );
                $found[] = trim( $output[0] );
            }
        }
        
        // If not found, trying with namespace of original class or if she's declared in configuration
        if( empty( $found ) ) {
            if( class_exists( $reflectionClass->getNamespaceName() . '\\' . $classname )
                || interface_exists( $reflectionClass->getNamespaceName() . '\\' . $classname ) ) {
                
                return $reflectionClass->getNamespaceName() . '\\' . $classname;
            }
            
            if( isset( $this->configuration[$classname] ) ) {
                
                return $classname;
            }
            
            throw new NotFoundException( 'Autowiring encountered an error: class ' . $reflectionClass->getNamespaceName() . '\\' . $classname . ' cannot be resolved, mentioned in ' . $reflectionClass->getName() );
        }
        
        if( count( $found ) === 1 ) {
            return $found[0];
        }
        
        // If it has been found several times, search in configuration, if all definition match, class is unresolved
        $defined = array();
        
        foreach( $found as $fullname ) {
            if( isset( $this->configuration[$fullname] ) ) {
                $defined[] = $fullname;
            }
        }
        
        if( empty( $defined ) || count( $defined ) > 1 ) {
            throw new NotFoundException( 'Autowiring encountered an error: impossible of resolved the class ' . $classname . ' mentionned in ' . $reflectionClass->getName() );
        }
        
        return $defined[0];
    }
    
    
    private function initConfig( ConfigHandler $configHandler ): void
    {
        /** @var CacheHandlerInterface $cacheHandler */
        $cacheHandler = $this->get( CacheHandler::class );
        
        if( !empty( $cache = $cacheHandler->get( self::CACHE_NAME, self::CACHE_NAME ) ) ) {
            $this->configuration = array_merge( $this->configuration, $cache );
            
            return;
        }
        
        $config = $configHandler->get( ConfigStoreInterface::DEFAULT_CONTAINER )['services'];
        
        $this->addClass( EventListenerInterface::class, EventListener::class )
             ->addClass( EventSubscriberInterface::class, EventSubscriber::class )
             ->addClass( ConfigStoreInterface::class, ConfigHandler::class )
             ->addClass( InstallerHandlerInterface::class, InstallerHandler::class )
             ->addClass( ContainerInterface::class, Container::class )
             ->addClass( RouteHandlerInterface::class, RouteHandler::class );
        
        /** @var InstallerHandlerInterface $installHandler */
        $installHandler = new InstallerHandler( $this->instance[ConfigHandler::class] );
        
        foreach( $installHandler->getPackages() as $nomessInstaller ) {
            foreach( $nomessInstaller->container() as $interface => $mapping ) {
                $this->addClass( $interface, $mapping );
            }
        }
        
        if( is_array( $config ) ) {
            foreach( $config as $class => $value ) {
                $this->addClass( $class, $value );
            }
        }
        
        $cacheHandler->add( self::CACHE_NAME, [
            'value' => $this->configuration
        ] );
    }
    
    
    private function addClass( string $class, $value ): self
    {
        if( $this->isException( $class, $value ) ) {
            return $this;
        }
        
        if( array_key_exists( $class, $this->configuration ) ) {
            if( is_array( $this->configuration[$class] ) ) {
                if( is_array( $value ) ) {
                    foreach( $value as $param => $classname ) {
                        if( is_string( $param ) ) {
                            if( array_key_exists( $param, $this->configuration[$class] ) ) {
                                throw new ConflictException( 'The parameter name "' . $param . '" already exists for "' . $class . '::class"' );
                            }
                            
                            $this->configuration[$class][$param] = $classname;
                            continue;
                        }
                        
                        $this->configuration[$class][] = $classname;
                    }
                } else {
                    $currentValue = $this->configuration[$class];
                    
                    $this->configuration[$class][] = $currentValue;
                    $this->configuration[$class][] = $value;
                }
            }
            
            return $this;
        }
        
        $this->configuration[$class] = $value;
        
        return $this;
    }
    
    
    private function isException( string $class, $value ): bool
    {
        if( $class === EventSubscriberInterface::class ) {
            $this->configuration[$class][] = $value;
            
            return TRUE;
        }
        
        return FALSE;
    }
}
