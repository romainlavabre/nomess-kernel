<?php

namespace NoMess\Router;

use Twig\Environment;
use DI\ContainerBuilder;
use NoMess\SubjectInterface;
use NoMess\ObserverInterface;
use NoMess\DiBuilder\DiBuilder;
use Twig\Loader\FilesystemLoader;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpSession\HttpSession;
use NoMess\HttpResponse\HttpResponse;
use NoMess\Router\Builder\BuildRoutes;
use NoMess\DataManager\Builder\BuilderDataManager;

class Router implements SubjectInterface
{

    private const ROUTING                   = ROOT . "App/var/cache/routes/routing.xml";
    private const DEFINITION                = ROOT . 'App/config/di-definitions.php';

    private const CACHE_ROUTING             = ROOT . 'App/var/cache/routes/routing.xml';
    private const CACHE_DATA_MANAGER        = ROOT . 'App/var/cache/mondata.xml';

    private const BASE_ENVIRONMENT          = 'Web/public/';


    /**
     *
     * @var HttpSession
     */
    private $HttpSession;


    /**
     *
     * @var Container
     */
    private $container;


    /**
     *
     * @var ObserverInterface
     */
    private $observer = array();




    public function __construct()
    {

        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        $builder->addDefinitions(self::DEFINITION);
        $this->container = $builder->build();

        $this->HttpSession = $this->container->get(HttpSession::class);

        $this->HttpSession->initSession();

        $this->resetCache();

        $this->attach();
        $this->notify();

        
        if(!file_exists(self::CACHE_DATA_MANAGER)){
            $buildMonitoring = new BuilderDataManager();
            $buildMonitoring->builderManager();
        }


        if(!file_exists(self::CACHE_ROUTING)){
            $buildRouting = new BuildRoutes(self::CACHE_ROUTING);
            $buildRouting->build();
        }
    }


    /**
     * Routeur
     *
     * @return void
     */
    public function getRoute() : void
    {
        
        $vController = null;
        $method = null;
        
        $file = simplexml_load_file(self::ROUTING);

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

        
        foreach($file->routes as $value){
            if((string)$value->attributes()['url'] === $_GET['p']){
                $controller = (string)$value->controller;
                $path = (string)$value->path;
                $auth = (string)$value->auth;
                
                if(!empty($auth) && !isset($_SESSION[$auth])){
                    header('HTTP/1.0 403 Permissions denied');

                    $tabError = require ROOT . 'App/config/error.php';

                    if(strpos($tabError['403'], '.twig')){
                        $this->bindTwig($tabError['403']);
                    }else{
                        include(ROOT . $tabError['403']);
                    }
                    die;
                }

                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    $action = 'doPost';
                    $method = "POST";
                }else{
                    $action = 'doGet';
                    $method = "GET";
                }
                
                break;
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

            header('HTTP/1.0 404 NOT FOUND');

            $tabError = require ROOT . 'App/config/error.php';

            if(strpos($tabError['404'], '.twig')){
                $this->bindTwig($tabError['404']);
            }else{
                include(ROOT . $tabError['404']);
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
            @unlink(ROOT . 'App/var/cache/routes/routing.xml');
            unset($_POST);
        }

        if(isset($_POST['resetCacheMon'])){
            @unlink(ROOT . 'App/var/cache/mondata.xml');
            unset($_POST);
        }
    }



    /**
     * Attache les observeurs
     *
     * @return void
     */
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

    

    /**
     * Notify les observeurs d'un changement d'état
     *
     * @return void
     */
    public function notify(): void
    {
        foreach($this->observer as $value){
            $value->notifiedInput();
        }
    }


    /**
     * Charge une erreur avec twig
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
			'WEBROOT' => WEBROOT
        ]);	
    }
}