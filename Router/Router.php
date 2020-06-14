<?php

namespace NoMess\Router;

use NoMess\Components\Slug\Slug;
use NoMess\Container\Container;
use NoMess\Exception\WorkException;
use NoMess\Router\Builder\Builder;
use NoMess\Service\Helpers\Response;
use Twig\Environment;
use NoMess\SubjectInterface;
use NoMess\ObserverInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use NoMess\Http\HttpRequest;
use NoMess\Http\HttpSession;
use NoMess\Http\HttpResponse;



class Router implements SubjectInterface
{

    use Response;

    private const CACHE_ROUTING             = ROOT . 'App/var/cache/routes/route.php';
    private const COMPONENTS_CONFIG         = ROOT . 'App/config/components.php';

    private const BASE_ENVIRONMENT          = 'public';



    private HttpSession $HttpSession;

    private Container $container;


    /**
     *
     * @var ObserverInterface[]
     */
    private array $observer;


    /**
     * @throws WorkException
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->container = new Container();

        $this->HttpSession = $this->container->get(HttpSession::class);
        $this->HttpSession->initSession();

        if(NOMESS_CONTEXT === 'DEV') {
            if(isset($_POST['resetCache'])){
                opcache_reset();
                unset($_POST);
            }

            if(isset($_POST['invalide'])){
                opcache_invalidate($_POST['invalide'], true);
                unset($_POST);
            }

            $this->controlCacheFile();
        }

        if(!file_exists(self::CACHE_ROUTING)){
            $builder = new Builder();
            $builder->buildRoute();
        }


        $this->maintenance();
        $this->formToken();

        $this->attach();
        $this->notify();

        $this->initiator();


    }


    /**
     * Router
     *
     * @return void
     */
    public function initiator() : void
    {
        $applicationRoutes = require self::CACHE_ROUTING;
        $applicationRoutes = unserialize($applicationRoutes);

        $config = $this->routeResolver($_GET['p'], $applicationRoutes);

        $this->filterResolver($config['filters']);

        $controller = $config['controller'];
        $action = $this->getMethodToCall();



        if(NOMESS_CONTEXT === 'DEV') {
            $result = explode('\\', $controller);
            $vController = $result[count($result) - 1];
            $method = ($action === 'doGet') ? 'GET' : 'POST';
            $_SESSION['nomess_toolbar'] = ['action' => $action, 'controller' => $vController, 'method' => $method];
        }

        if(class_exists($controller)) {
            $controller = $this->container->get($controller);
            $controller->$action($this->container->get(HttpResponse::class), $this->container->get(HttpRequest::class));
        }else{
            $this->response(404);
        }
    }


    /**
     * Attach observers
     *
     * @return void
     */
    public function attach() : void
    {
        $componentConfig = require self::COMPONENTS_CONFIG;

        if($componentConfig !== null){
            foreach($componentConfig as $key => $value){
                if($value !== false && isset(class_implements($key)[ObserverInterface::class])){
                    $this->observer[] = $this->container->get($key);
                }
            }
        }
    }



    /**
     * Notify the observer that state has changed
     *
     * @return void
     */
    public function notify(): void
    {
        if(isset($this->observer)) {
            foreach ($this->observer as $value) {
                $value->notifiedInput();
            }
        }
    }


    /**
     * Load error template with twig engine
     *
     * @param string $template
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function bindTwig(string $template) : void
    {
        $loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
        $engine = new Environment($loader, [
            'cache' => false,
        ]);

        $engine->addExtension(new \Twig\Extension\DebugExtension());

        echo $engine->render($template, [
            'WEBROOT' => WEBROOT,
            'URL' => URL
        ]);
    }


    /**
     * Control existing cache file of route
     *
     */
    private function controlCacheFile(): void
    {
        if(!file_exists(self::CACHE_ROUTING)){
            $builder = new Builder();
            $builder->buildRoute();
        }
    }



    private function formToken(): void
    {
        if($this->HttpSession->get('_token') !== null &&
            (isset($_POST['_token']) || isset($_GET['_token']))){

            if((isset($_POST['_token']) && $_POST['_token'] !== $this->HttpSession->get('_token'))
                || (isset($_GET['_token']) && $_GET['_token'] !== $this->HttpSession->get('_token'))){

                $this->response(401);
            }
        }
    }

    private function maintenance(): void
    {
        if(getenv('NM_MAINTENANCE') === true){
            $this->response(503);
        }
    }

    private function filterResolver(?array $filters): void
    {
        if (!empty($filters)) {

            foreach ($filters as $filter) {
                $className = 'App\\Filters\\' . $filter;

                $filter = $this->container->get($className);
                $filter->filtrate($this->container->get(HttpRequest::class), $this->container->get(HttpResponse::class));

            }
        }
    }

    private function routeResolver(string $url, array $applicationRoutes): array
    {
        foreach ($applicationRoutes as $route => $value){

            if (strpos($url, '/' .getenv('NM_KEYWORD_PARAMETER') . '/')) {
                $tmp = explode('/' .getenv('NM_KEYWORD_PARAMETER') . '/', $url);

                if($tmp[0] === $route) {
                    $parameters = $tmp[1];
                    $this->parameterResolver($parameters);

                    return $value;
                }
            }elseif($route === $url){
                return $value;
            }
        }

        $this->response(404);
    }

    private function parameterResolver(string $unformatedParameters): void
    {

        $slug = $this->container->get(Slug::class);

        $parameters = explode('/', $unformatedParameters);


        //1 = key, 2 = value
        $last = 1;

        foreach ($parameters as $parameter){

            if($last === null){
                $_GET[$parameter] = null;
                $last = $parameter;
            }else{
                $_GET[$last] = ($slug->searchSlug($parameter) !== null) ? $slug->searchSlug($parameter) : $parameter;
                $last = null;
            }
        }
    }

    private function getMethodToCall(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return 'doPost';
        } else {
            return 'doGet';
        }
    }

}