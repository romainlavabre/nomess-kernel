<?php

namespace Nomess\Initiator;


use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Component\Config\Exception\ConfigurationNotFoundException;
use Nomess\Container\Container;
use Nomess\Http\HttpRequest;
use Nomess\Http\HttpResponse;
use Nomess\Http\HttpSession;
use Nomess\Initiator\Filters\FilterResolver;
use Nomess\Initiator\Route\RouteResolver;

class Initiator
{
    
    private Container    $container;
    private HttpRequest  $request;
    private HttpResponse $response;
    
    
    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->container->get( HttpSession::class )->initSession();
        $this->request  = $this->container->get( HttpRequest::class );
        $this->response = $this->container->get( HttpResponse::class );
    }
    
    
    /**
     * @return mixed|void
     * @throws ConfigurationNotFoundException
     */
    public function initializer(): HttpResponse
    {
        
        $arrayEntryPoint = $this->getRoute();
        $this->callFilters();
        
        /** @var ConfigStoreInterface $config */
        $config = $this->container->get( ConfigStoreInterface::class );
        
        if( $config->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['status'] === 'disable' ) {
            return $this->response->response_code( 503 );
        }
        
        if( empty( $arrayEntryPoint ) ) {
            return $this->response->response_code( 404 );
        }
        
        
        if( $arrayEntryPoint['request_method'] === NULL
            || strpos( $arrayEntryPoint['request_method'], $_SERVER['REQUEST_METHOD'] ) !== FALSE ) {
            
            $_SESSION['app']['toolbar'] = [
                'controller' => basename( $arrayEntryPoint['controller'] ),
                'method'     => $arrayEntryPoint['method']
            ];
            
            
            $this->container->callController( $arrayEntryPoint['controller'], $arrayEntryPoint['method'] );
            
            return $this->response;
        }
        
        return $this->response->response_code( 405 );
    }
    
    
    private function getRoute(): ?array
    {
        return $this->container->get( RouteResolver::class )->resolve();
    }
    
    
    private function callFilters(): void
    {
        $this->container->get( FilterResolver::class )->resolve( $_GET['p'] );
    }
}
