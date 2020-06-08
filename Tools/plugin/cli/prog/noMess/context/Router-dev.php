<?php

namespace NoMess\Router;

use NoMess\Container\Container;
use NoMess\Router\Builder\Builder;
use Twig\Environment;
use NoMess\SubjectInterface;
use NoMess\ObserverInterface;
use Twig\Loader\FilesystemLoader;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpSession\HttpSession;
use NoMess\HttpResponse\HttpResponse;




class Router implements SubjectInterface
{

    private const CACHE_ROUTING             = ROOT . 'App/var/cache/routes/route.xml';
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
     * @throws \NoMess\Exception\WorkException
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->container = new Container();

        $this->HttpSession = $this->container->get(HttpSession::class);
        $this->HttpSession->initSession();

        $this->resetCache();

        $this->attach();
        $this->notify();

        $this->controlCacheFile();
    }


    /**
     * Router
     *
     * @return void
     */
    public function getRoute() : void
    {

        $vController = null;
        $method = null;

        $route = require self::CACHE_ROUTING;
        $route = unserialize($route);

        $controller = null;
        $path = null;

        if(strpos($_GET['p'], 'param') !== false){
            $get = array();

            $findParam = false;

            $lastValue = null;

            $i = 1;

            $url = explode('/', $_GET['p']);

            foreach($url as $value){
                if($findParam === false && strpos($value, 'param') === false){
                    $get[] = $value;
                }else if($findParam === false){
                    $findParam = true;
                }else{
                    if(is_float($i / 2)){
                        $_GET[$value] = null;
                        $lastValue = $value;
                    }else{
                        $_GET[$lastValue] = $value;
                    }

                    $i++;
                }
            }

            $_GET['p'] = implode('/', $get);
        }

        if(isset($route[$_GET['p']])) {
            $controller = $route[$_GET['p']]['controller'];
            $path = $route[$_GET['p']]['path'];
            $useFilter = $route[$_GET['p']]['filter'];

            if (!empty($useFilter)) {
                $className = 'App\\Filters\\' . $useFilter;

                $filter = $this->container->get($className);
                $filter->filtrate($this->container->get(HttpRequest::class), $this->container->get(HttpResponse::class));

            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = 'doPost';
                $method = "POST";
            } else {
                $action = 'doGet';
                $method = "GET";
            }
        }

        $result = explode('\\', $controller);
        $vController = $result[count($result) - 1];

        $this->bufferExclude();


        if(file_exists($path) && !empty($controller)){
            $controller = $this->container->get($controller);

            $_SESSION['nomess_toolbar'] = [1 => $action, 2 => $vController, 3 => $method];

            $controller->$action($this->container->get(HttpResponse::class), $this->container->get(HttpRequest::class));
        }else{

            http_response_code(404);

            $tabError = require ROOT . 'App/config/error.php';

            if(strpos($tabError[404], '.twig')){
                if(file_exists(ROOT . 'Web/public/' . $tabError[404])) {
                    $this->bindTwig($tabError[404]);
                }
            }else{
                if(file_exists(ROOT . $tabError[404])) {
                    include(ROOT . $tabError[404]);
                }
            }
            die;
        }

    }


    private function bufferExclude() : void
    {
        if(isset($_GET['buffer'])){
            $_SESSION['nomess_buffer'] = $_GET['buffer'];
        }
    }

    private function resetCache() : void
    {
        if(isset($_POST['resetCache'])){
            opcache_reset();
            unset($_POST);
        }

        if(isset($_POST['invalide'])){
            opcache_invalidate($_POST['invalide'], true);
            unset($_POST);
        }

        if(isset($_POST['resetCacheRoute'])){
            @unlink(self::CACHE_ROUTING);
            unset($_POST);
        }

        if(isset($_POST['resetCacheMon'])){
            @unlink(ROOT . 'App/var/cache/dm/datamanager.xml');
            unset($_POST);
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
     *
     * @return void
     */
    public function bindTwig(string $template) : void
    {
        $loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
        $this->engine = new Environment($loader, [
            'cache' => false,
        ]);

        $this->engine->addExtension(new \Twig\Extension\DebugExtension());

        echo $this->engine->render($template, [
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
}