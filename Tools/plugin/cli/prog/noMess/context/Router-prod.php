<?php

namespace NoMess\Router;

use NoMess\Container\Container;
use NoMess\Router\Builder\Builder;
use Twig\Environment;
use NoMess\ObserverInterface;
use Twig\Loader\FilesystemLoader;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpSession\HttpSession;
use NoMess\HttpResponse\HttpResponse;
use NoMess\Router\Builder\BuildRoutes;
use NoMess\DataManager\Builder\BuilderDataManager;

class Router
{

    private const CACHE_ROUTING             = ROOT . 'App/var/cache/routes/route.xml';
    private const CACHE_DATA_MANAGER        = ROOT . 'App/var/cache/dm/datamaner.xml';

    private const BASE_ENVIRONMENT          = 'public';


    private HttpSession $HttpSession;

    private Container $container;

    private array $observer = array();



    public function __construct()
    {

        $this->container = new Container();

        $this->HttpSession = $this->container->get(HttpSession::class);
        $this->HttpSession->initSession();


        $this->attach();
        $this->notify();


        if(!file_exists(self::CACHE_ROUTING)){
            $builder = new Builder();
            $builder->buildRoute();
        }

    }

    public function getRoute() : void
    {

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
        
        if(isset($route[$_GET['p']])){
            $controller = $route[$_GET['p']]['controller'];
            $path = $route[$_GET['p']]['path'];
            $auth = $route[$_GET['p']]['auth'];

            if(!empty($auth) && !isset($_SESSION[$auth])){
                header('HTTP/1.0 403 Permissions denied');

                $tabError = require ROOT . 'App/config/error.php';

                if(strpos($tabError['403'], '.twig')){
                    if(file_exists(ROOT . 'Web/' . $tabError['403'])) {
                        $this->bindTwig($tabError['403']);
                    }
                }else{
                    if(file_exists(ROOT . $tabError['403'])) {
                        include(ROOT . $tabError['403']);
                    }
                }
                die;
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $action = 'doPost';
            }else{
                $action = 'doGet';
            }

        }


        $this->bufferExclude();

        if(file_exists($path) && !empty($controller)){
            $controller = $this->container->get($controller);
            
            $controller->$action($this->container->get(HttpResponse::class), $this->container->get(HttpRequest::class));
        }else{

            header('HTTP/1.0 404 NOT FOUND');

            $tabError = require ROOT . 'App/config/error.php';

            if(strpos($tabError['404'], '.twig')){
                if(file_exists(ROOT . 'Web/' . $tabError['404'])) {
                    $this->bindTwig($tabError['404']);
                }
            }else{
                if(file_exists(ROOT . $tabError['404'])) {
                    include(ROOT . $tabError['404']);
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

    public function attach() : void
    {
        $componentConfig = require ROOT . 'App/config/component.php';

        if($componentConfig !== null){
            foreach($componentConfig as $key => $value){
                if($value !== false && isset(class_implements($key)[ObserverInterface::class])){
                    $this->observer[] = $this->container->get($key);
                }
            }
        }
    }

    public function notify(): void
    {
        foreach($this->observer as $value){
            $value->notifiedInput();
        }
    }

    public function bindTwig(string $template) : void
    {
        $loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
		$this->engine = new Environment($loader, [
			'cache' => false,
		]);

        $this->engine->addExtension(new \Twig\Extension\DebugExtension());
        
        echo $this->engine->render($template, [
            'URL' => URL,
			'WEBROOT' => WEBROOT
        ]);	
    }
}