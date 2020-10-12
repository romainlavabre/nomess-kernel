<?php

namespace Nomess\Http;


use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Container\Container;
use Nomess\Exception\NotFoundException;
use Nomess\Initiator\Route\RouteHandlerInterface;
use Nomess\Tools\Twig\Form\ComposeExtension;
use Nomess\Tools\Twig\Form\CsrfExtension;
use Nomess\Tools\Twig\Form\FieldExtension;
use Nomess\Tools\Twig\Form\ValueExtension;
use Nomess\Tools\Twig\PathExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HttpResponse
{
    
    private const CACHE_ROUTE_NAME       = 'routes';
    private const CONFIG_TWIG            = 'twig';
    private const SESSION_NOMESS_SCURITY = 'nomess_session_security';
    private const SESSION_DATA           = 'nomess_persiste_data';
    private HttpRequest            $request;
    private CacheHandlerInterface  $cacheHandler;
    private ConfigStoreInterface   $configStore;
    private ResponseHeaderInterface $headers;
    private array                  $return = array();
    private ?array                 $data   = array();
    
    
    /**
     * @param HttpRequest $request
     * @param CacheHandlerInterface $cacheHandler
     * @param ConfigStoreInterface $configStore
     */
    public function __construct(
        HttpRequest $request,
        CacheHandlerInterface $cacheHandler,
        ConfigStoreInterface $configStore )
    {
        $this->request      = $request;
        $this->cacheHandler = $cacheHandler;
        $this->configStore  = $configStore;
        $this->headers = new HttpHeader();
    }
    
    
    /**
     * forward data
     *
     * @param HttpRequest|null $request
     * @return self
     */
    public final function forward( ?HttpRequest $request ): self
    {
        
        if( $request !== NULL ) {
            $this->data = $request->getParameters();
            
            if(isset( $_SESSION[self::SESSION_NOMESS_SCURITY])){
                unset( $_SESSION[self::SESSION_NOMESS_SCURITY] );
    
            }
        }
        
        return $this;
    }
    
    
    /**
     * Create an cookie accept an array with multiple entry
     *
     * @param string $name
     * @param mixed $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return HttpResponse
     */
    public final function addCookie( string $name, $value = "", int $expires = 0, string $path = "", string $domain = "", bool $secure = FALSE, bool $httponly = FALSE ): HttpResponse
    {
        if( is_array( $value ) ) {
            foreach( $value as $key => $val ) {
                setcookie( $name[$key], $val, $expires, $path, $domain, $secure, $httponly );
            }
        } else {
            setcookie( $name, (string)$value, $expires, $path, $domain, $secure, $httponly );
        }
        
        return $this;
    }
    
    
    /**
     * Delete the cookie correspondence with index variable
     *
     * @param string $index
     * @return HttpResponse
     */
    public final function removeCookie( string $index ): HttpResponse
    {
        setcookie( $index, NULL, -1, '/' );
        
        return $this;
    }
    
    
    public function response_code( int $code ): HttpResponse
    {
        $this->headers->responseCode( $code);
        $config       = $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS );
        $tabError     = $config['error_pages']['codes'];
        $pathTemplate = $config['general']['path']['default_template'];
        
        if( file_exists( $pathTemplate . $tabError[$code] ) ) {
            $this->template( $tabError[$code] );
            $this->show();
        }
        
        return $this;
    }
    
    
    public function template( string $template, int $response_code = 200 ): HttpResponse
    {
        
        $this->headers->responseCode( $response_code);
        
        $time = 0;
        
        if( function_exists( 'xdebug_time_index' ) ) {
            $time = xdebug_time_index();
        }
        
        
        $loader = new FilesystemLoader( $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS)['general']['path']['default_template'] );
    
        $engine = new Environment( $loader, [
            'debug' => NOMESS_CONTEXT === 'DEV' ? TRUE : FALSE,
            'cache' => NOMESS_CONTEXT === 'DEV' ? FALSE : ROOT . 'var/cache/twig/',
        ] );
        
        $this->addTwigExtension( $engine );
        
        $this->return[] = $engine->render( $template, is_array( $this->data ) ? $this->data : [] );
        
        $this->getDevToolbar( $time );
        
        
        return $this;
    }
    
    
    /**
     * Redirects to a local resource, if the forward method is called, pending operations
     * will be executed and the data will be presented in the following context
     *
     * @param string $routeName
     * @param array|null $parameters
     * @param int $response_code
     * @return HttpResponse
     * @throws NotFoundException
     */
    public final function redirectToLocal( string $routeName, array $parameters = [], int $response_code = 302 ): HttpResponse
    {
        $this->headers->responseCode( $response_code );
        
        if( isset( $this->data ) ) {
            unset( $this->data['POST'], $this->data['GET'] );
            $_SESSION[self::SESSION_DATA] = $this->data;
        }
        
        /** @var RouteHandlerInterface $routeHandler */
        $routeHandler = Container::getInstance()->get( RouteHandlerInterface::class );
        $this->headers->set( HttpHeader::LOCATION, $route = $routeHandler->getUri( $routeName, $parameters ));
        
        if( $route === NULL ) {
            throw new NotFoundException( 'Your route "' . $routeName . '" was not found' );
        }
        
        return $this;
    }
    
    
    /**
     * Redirects to an external resource, if the forward method is called, pending operations will be executed
     *
     * @param string $url
     * @param int $response_code
     * @return HttpResponse
     */
    public final function redirectToOutside( string $url, int $response_code = 302 ): HttpResponse
    {
        $this->headers->responseCode( $response_code);
        $this->headers->set( HttpHeader::LOCATION, $url);
        
        return $this;
    }
    
    
    public final function json( array $data, int $response_code = 200 ): HttpResponse
    {
        $this->headers->responseCode( $response_code);
        
        $this->return[] = json_encode( $data );
        
        return $this;
    }
    
    
    private function getDevToolbar( $time ): void
    {
        $toolbarActive = $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['toolbar'];
        
        if( ( $toolbarActive === 'auto' && NOMESS_CONTEXT === 'PROD' ) || $toolbarActive === 'disable' ) {
            return;
        }
        
        global $controllerShortName, $method;
        
        $this->return[] = [
            'param'    => [
                'controller' => $controllerShortName,
                'method'     => $method,
                'action'     => $_SERVER['REQUEST_METHOD']
            ],
            'template' => require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php'
        ];
    }
    
    
    private function addTwigExtension( Environment $environment ): void
    {
        if( NOMESS_CONTEXT === 'DEV' ) {
            $environment->addExtension( new \Twig\Extension\DebugExtension() );
        }
    
        $environment->addExtension( new PathExtension() );
        $environment->addExtension( new CsrfExtension() );
        $environment->addExtension( new ComposeExtension() );
    
        if( is_array( $this->data ) ) {
            $environment->addExtension( $valueExtension = new ValueExtension( $this->data['POST'] ) );
            $environment->addExtension( new FieldExtension( $valueExtension ) );
        }
        
        if( $this->configStore->has( self::CONFIG_TWIG ) ) {
            
            $extensions = $this->configStore->get( self::CONFIG_TWIG )['extensions'];
            
            if( is_array( $extensions ) ) {
                foreach( $extensions as $extension ) {
                    $environment->addExtension( new $extension() );
                }
            }
        }
    }
    
    
    /**
     * @return ResponseHeaderInterface
     */
    public function getHeaders(): ResponseHeaderInterface
    {
        return $this->headers;
    }
    
    
    /**
     * Stop the php process
     */
    public function stop(): void
    {
        die();
    }
    
    
    public function show(): void
    {
        foreach( $this->return as $data ) {
            if( is_string( $data ) ) {
                echo $data;
            }
        }
    }
}
