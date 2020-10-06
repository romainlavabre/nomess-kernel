<?php


namespace Nomess\Container;


class Container implements ContainerInterface
{
    
    private Autowire         $autowire;
    private static Container $instance;
    
    
    private function __construct()
    {
        if( !isset( $this->autowire ) ) {
            $this->autowire = new Autowire( $this );
        }
    }
    
    
    public function get( string $classname )
    {
        return $this->autowire->get( $classname );
    }
    
    
    public function make( string $className )
    {
        return $this->autowire->make( $className );
    }
    
    public function getByReflectionParameter(\ReflectionParameter $reflectionParameter)
    {
        return $this->autowire->getByReflectionParameter( $reflectionParameter);
    }
    
    
    public static function getInstance(): Container
    {
        if( !isset( self::$instance ) ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
}
