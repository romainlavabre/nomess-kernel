<?php

namespace NoMess\Router;

use DI\ContainerBuilder;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpSession\HttpSession;
use NoMess\HttpResponse\HttpResponse;
use NoMess\Router\Builder\BuildRoutes;
use NoMess\DataManager\Builder\BuilderDataManager;
use NoMess\DiBuilder\DiBuilder;

class Router
{

    private $HttpSession;

    private $HttpRequest;

    private $HttpResponse;

    private $container;



    private const ROUTING                   = ROOT . "App/var/cache/routes/routing.xml";
    private const DEFINITION                = ROOT . 'App/config/di-definitions.php';

    private const CACHE_ROUTING             = ROOT . 'App/var/cache/routes/routing.xml';
    private const CACHE_DATA_MANAGER        = ROOT . 'App/var/cache/mondata.xml';



    public function __construct()
    {

        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        $builder->addDefinitions(self::DEFINITION);
        $this->container = $builder->build();

        $this->HttpSession = $this->container->get(HttpSession::class);
        $this->HttpRequest = $this->container->get(HttpRequest::class);
        $this->HttpResponse = $this->container->get(HttpResponse::class);

        $this->HttpSession->initSession();

        $this->resetCache();

        
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
     * @return array|null
     */
    public function getRoute() : ?array
    {
        
        $vController = null;
        $method = null;
        
        $file = simplexml_load_file(self::ROUTING);

        $controller = null;
        $path = null;
        

        foreach($file->routes as $value){
            if((string)$value->attributes()['url'] === $_GET['p']){
                $controller = (string)$value->controller;
                $path = (string)$value->path;
                $auth = (string)$value->auth;
                
                if(!empty($auth) && !isset($_SESSION[$auth])){
                    header('HTTP/1.0 403 Permissions denied');

                    $tabError = require ROOT . 'App/config/error.php';

                    include(ROOT . $tabError['403']);
                    die;
                }

                if(isset($_POST) && !empty($_POST)){
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

        if(file_exists($path) && !empty($controller)){	
            $controller = $this->container->get($controller);

            $controller->$action($this->HttpResponse, $this->HttpRequest);
            
            return [0 => $controller, 1 => $action, 2 => $vController, 3 => $method];
        }else{

            header('HTTP/1.0 404 NOT FOUND');

            $tabError = require ROOT . 'App/config/error.php';

            include(ROOT . $tabError['404']);
            die;
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
            unlink(ROOT . 'App/var/cache/routes/routing.xml');
            unset($_POST);
        }

        if(isset($_POST['resetCacheMon'])){
            unlink(ROOT . 'App/var/cache/mondata.xml');
            unset($_POST);
        }
    }
}