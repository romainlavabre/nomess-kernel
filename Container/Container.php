<?php


namespace Nomess\Container;


class Container implements ContainerInterface
{
    
    private Autowire         $autowire;
    private static Container $instance;
    
    
    private function __construct()
    {
    }
    
    
    public function get( string $classname )
    {
        $this->initAutowire();
        
        return $this->autowire->get( $classname );
    }
    
    
    public function make( string $className )
    {
        $this->initAutowire();
        
        return $this->autowire->make( $className );
    }
    
    
    public function callController( string $classname, string $methodName )
    {
        $this->initAutowire();
        $this->autowire->force['method'] = $methodName;
        $this->autowire->force['class']  = $classname;
        
        return $this->autowire->make( $classname );
    }
    
    
    public static function getInstance(): Container
    {
        if( !isset( self::$instance ) ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    
    private function initAutowire(): void
    {
        if( !isset( $this->autowire ) ) {
            $this->autowire = new Autowire( $this );
        }
    }
}
