<?php

namespace Nomess\Http;


use InvalidArgumentException;
use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Component\Cache\Exception\InvalidSendException;
use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Component\Config\Exception\ConfigurationNotFoundException;
use Nomess\Container\Container;
use Nomess\Exception\NotFoundException;
use Nomess\Initiator\Route\RouteBuilder;
use Nomess\Tools\Twig\Form\ComposeExtension;
use Nomess\Tools\Twig\Form\CsrfExtension;
use Nomess\Tools\Twig\Form\FieldExtension;
use Nomess\Tools\Twig\Form\ValueExtension;
use Nomess\Tools\Twig\PathExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class HttpResponse
{
    
    private const CACHE_ROUTE_NAME       = 'routes';
    private const CONFIG_TWIG            = 'twig';
    private const SESSION_NOMESS_SCURITY = 'nomess_session_security';
    private const SESSION_DATA           = 'nomess_persiste_data';
    private HttpRequest            $request;
    private CacheHandlerInterface  $cacheHandler;
    private ConfigStoreInterface   $configStore;
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
            
            unset( $_SESSION[self::SESSION_NOMESS_SCURITY] );
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
        http_response_code( $code );
        $config       = $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS );
        $tabError     = $config['error_pages']['codes'];
        $pathTemplate = $config['general']['path']['default_template'];
        
        if( file_exists( $pathTemplate . $tabError[$code] ) ) {
            $this->template( $tabError[$code] );
        }
        
        
        return $this;
    }
    
    
    public function template( string $template ): HttpResponse
    {
        $time = 0;
        
        if( NOMESS_CONTEXT === 'DEV' ) {
            $time = xdebug_time_index();
        }
        
        $loader = new FilesystemLoader( ROOT . 'templates' );
        
        if( NOMESS_CONTEXT === 'DEV' ) {
            $engine = new Environment( $loader, [
                'debug' => TRUE,
                'cache' => FALSE,
            ] );
            
            $engine->addExtension( new \Twig\Extension\DebugExtension() );
        } else {
            $engine = new Environment( $loader, [
                'cache' => ROOT . 'var/cache/twig/'
            ] );
        }
        
        $engine->addExtension( new PathExtension() );
        $engine->addExtension( new CsrfExtension() );
        $engine->addExtension( new ComposeExtension() );
        
        if( is_array( $this->data ) ) {
            $engine->addExtension( $valueExtension = new ValueExtension( $this->data['POST'] ) );
            $engine->addExtension( new FieldExtension( $valueExtension ) );
        }
        
        $this->addTwigExtension( $engine );
        
        $this->return[] = $engine->render( $template, is_array( $this->data ) ? $this->data : [] );
        
        if( NOMESS_CONTEXT === 'DEV' ) {
            $this->getDevToolbar( $time );
        }
        
        return $this;
    }
    
    
    /**
     * Redirects to a local resource, if the forward method is called, pending operations
     * will be executed and the data will be presented in the following context
     *
     * @param string $routeName
     * @param array|null $parameters
     * @return void
     * @throws NotFoundException
     * @throws InvalidSendException
     * @throws ConfigurationNotFoundException
     */
    public final function redirectToLocal( string $routeName, ?array $parameters ): void
    {
        
        if( isset( $this->data ) ) {
            unset( $this->data['POST'], $this->data['GET'] );
            $_SESSION[self::SESSION_DATA] = $this->data;
        }
        
        $routes = NULL;
        
        if( $this->cacheHandler->get( self::CACHE_ROUTE_NAME, 'routes_match' ) === NULL ) {
            $routes = Container::getInstance()->get( RouteBuilder::class )->build();
        }
        
        foreach( $routes as $key => $route ) {
            if( $route['name'] === $routeName ) {
                if( strpos( $key, '{' ) !== FALSE ) {
                    $sections = explode( '/', $key );
                    
                    foreach( $sections as &$section ) {
                        
                        if( strpos( $section, '{' ) !== FALSE ) {
                            if( !empty( $parameters ) && array_key_exists( str_replace( [ '{', '}' ], '', $section ), $parameters ) ) {
                                $section = $parameters[str_replace( [ '{', '}' ], '', $section )];
                            } else {
                                throw new InvalidArgumentException( 'Missing an dynamic data in your url' );
                            }
                        }
                    }
                    
                    $key = implode( '/', $sections );
                }
                
                if( strpos( $key, '{' ) ) {
                    throw new InvalidArgumentException( 'Missing an dynamic data in your url' );
                }
                
                header( "Location: $key" );
                die;
            }
        }
        
        throw new NotFoundException( "Your route $routeName has not found" );
    }
    
    
    /**
     * Redirects to an external resource, if the forward method is called, pending operations will be executed
     *
     * @param string $url
     * @return void
     */
    public final function redirectToOutside( string $url ): void
    {
        header( "Location: $url" );
        die();
    }
    
    
    public final function json( array $data ): HttpResponse
    {
        $this->return[] = json_encode( $data );
        
        return $this;
    }
    
    
    private function getDevToolbar( $time ): void
    {
        $toolbarActive = $this->configStore->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['toolbar'];
        
        if( ( $toolbarActive === 'auto' && NOMESS_CONTEXT === 'PROD' ) || $toolbarActive === 'disable' ) {
            return;
        }
        
        $controller = NULL;
        $method     = NULL;
        $action     = NULL;
        
        if( isset( $_SESSION['app']['toolbar'] ) ) {
            $controller = $_SESSION['app']['toolbar']['controller'];
            $method     = $_SESSION['app']['toolbar']['method'];
            $action     = $_SERVER['REQUEST_METHOD'];
            
            unset( $_SESSION['app']['toolbar'] );
        }
        
        $this->return[] = [
            'param'    => [
                'controller' => $controller,
                'method'     => $method,
                'action'     => $action
            ],
            'template' => require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php'
        ];
    }
    
    
    private function addTwigExtension( Environment $environment ): void
    {
        if( $this->configStore->has( self::CONFIG_TWIG ) ) {
            
            $extensions = $this->configStore->get( self::CONFIG_TWIG );
            
            foreach( $extensions as $extension ) {
                $environment->addExtension( new $extension() );
            }
        }
    }
    
    
    public function show(): void
    {
        foreach( $this->return as $data ) {
            if( is_array( $data ) ) {
                $controller = $data['param']['controller'];
                $method     = $data['param']['method'];
                $action     = $data['param']['action'];
                
                echo $data['template'];
            } else {
                echo $data;
            }
        }
    }
}
