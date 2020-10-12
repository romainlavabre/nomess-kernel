<?php

namespace Nomess\Initiator;


use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Component\Config\Exception\ConfigurationNotFoundException;
use Nomess\Component\Orm\EntityManagerInterface;
use Nomess\Container\Container;
use Nomess\Event\EventListenerInterface;
use NoMess\Exception\UnsupportedEventException;
use Nomess\Helpers\ReportHelper;
use Nomess\Http\HttpHeader;
use Nomess\Http\HttpRequest;
use Nomess\Http\HttpResponse;
use Nomess\Http\HttpSession;
use Nomess\Initiator\Filters\FilterResolver;
use Nomess\Initiator\Route\RouteHandlerInterface;
use Nomess\Initiator\Route\RouteResolver;

class Initiator
{
    
    use ReportHelper;
    
    private Container              $container;
    private HttpRequest            $request;
    private HttpResponse           $response;
    private EventListenerInterface $eventListener;
    private ConfigStoreInterface   $configStore;
    
    
    public function __construct()
    {
        $this->container     = Container::getInstance();
        $this->request       = $this->container->get( HttpRequest::class );
        $this->response      = $this->container->get( HttpResponse::class );
        $this->eventListener = $this->container->get( EventListenerInterface::class );
        $this->eventListener->notify( EventListenerInterface::AFTER_CONTAINER_INITIALIZER );
        $this->configStore = $this->container->get( ConfigStoreInterface::class );
    }
    
    
    /**
     * @return mixed|void
     * @throws ConfigurationNotFoundException
     * @throws UnsupportedEventException
     */
    public function initializer(): HttpResponse
    {
        $this->eventListener->notify( EventListenerInterface::BEFORE_ROUTE_RESOLVER );
        
        $arrayEntryPoint = $this->getRoute();
        $this->eventListener->notify( EventListenerInterface::AFTER_ROUTE_RESOLVER, $arrayEntryPoint );
        $this->eventListener->notify( EventListenerInterface::BEFORE_FILTER_RESOLVER );
        $this->callFilters();
        $this->eventListener->notify( EventListenerInterface::AFTER_FILTER_RESOLVER );
        
        /** @var ConfigStoreInterface $config */
        $config = $this->container->get( ConfigStoreInterface::class );
        
        if( $config->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['status'] === 'disable' ) {
            return $this->response->response_code( HttpHeader::HTTP_SERVICE_UNAVAILABLE );
        }
        
        if( empty( $arrayEntryPoint ) ) {
            return $this->response->response_code( HttpHeader::HTTP_NOT_FOUND );
        }
        
        
        if( $arrayEntryPoint[RouteHandlerInterface::REQUEST_METHODS] === NULL
            || in_array( $_SERVER['REQUEST_METHOD'], $arrayEntryPoint[RouteHandlerInterface::REQUEST_METHODS] ) ) {
            
            $toolbarActive = $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['toolbar'];
            
            if( ( $toolbarActive === 'auto' && NOMESS_CONTEXT === 'DEV' ) || $toolbarActive === 'enable' ) {
                global $controllerShortName, $method;
    
                $controllerShortName = ( new \ReflectionClass( $arrayEntryPoint[RouteHandlerInterface::CONTROLLER] ) )->getShortName();
                $method              = $arrayEntryPoint[RouteHandlerInterface::METHOD];
            
            }
    
    
            $controller = $this->container->get( $arrayEntryPoint[RouteHandlerInterface::CONTROLLER] );
            $this->eventListener->notify( EventListenerInterface::BEFORE_CALL_CONTROLLER );
            call_user_func_array( [ $controller, $arrayEntryPoint[RouteHandlerInterface::METHOD] ], $this->getArgumentController( $arrayEntryPoint ) );
            $this->eventListener->notify( EventListenerInterface::AFTER_CALL_CONTROLLER );
            
            return $this->response;
        }
        
        return $this->response->response_code( HttpHeader::HTTP_METHOD_NOT_ALLOWED );
    }
    
    
    private function getRoute(): ?array
    {
        return $this->container->get( RouteResolver::class )->resolve();
    }
    
    
    private function callFilters(): void
    {
        $this->container->get( FilterResolver::class )->resolve( $_SERVER['REQUEST_URI'] );
    }
    
    
    private function getArgumentController( array $entryPoint ): array
    {
        $arguments = [];
        
        $entityDirectory = $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['path']['default_entity'];
        
        foreach( ( new \ReflectionMethod( $entryPoint[RouteHandlerInterface::CONTROLLER], $entryPoint[RouteHandlerInterface::METHOD] ) )->getParameters() as $reflectionParameter ) {
            
            $instance = NULL;
            
            if( strpos( ( new \ReflectionClass( $reflectionParameter->getType()->getName() ) )->getFileName(), $entityDirectory ) !== FALSE
                && ( strpos( $entryPoint[RouteHandlerInterface::ROUTE], '{' . $paramName = $reflectionParameter->getName() . '}' ) !== FALSE
                     || strpos( $entryPoint[RouteHandlerInterface::ROUTE], '{id}' ) !== FALSE ) ) {
                
                foreach( explode( '/', $entryPoint[RouteHandlerInterface::ROUTE] ) as $key => $value ) {
                    if( $value === '{id}' || $value === "{$paramName}" ) {
                        
                        /** @var EntityManagerInterface $entityManager */
                        $entityManager = Container::getInstance()->get( EntityManagerInterface::class );
                        
                        $instance = $entityManager->find( $reflectionParameter->getType()->getName(), (int)explode( '/', $_SERVER['REQUEST_URI'] )[$key] );
                        
                        if( $instance === NULL && !$reflectionParameter->getType()->allowsNull() ) {
                            $this->report( 'Resource "' . $reflectionParameter->getType()->getName() . '" was not found, 404 error returned' );
                            
                            $this->response->response_code( 404 );
                        }
                    }
                }
            } else {
                $instance = $this->container->getByReflectionParameter( $reflectionParameter );
            }
            
            $arguments[] = $instance;
        }
        
        return $arguments;
    }
}
