<?php

namespace NoMess\Router;

use DI\Container;
use NoMess\Launcher;
use DI\ContainerBuilder;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;
use NoMess\Router\Builder\BuildRoutes;
use NoMess\DataManager\Builder\BuildDataManager;

class Router
{

    private $HttpSession;

    private $HttpRequest;

    private $HttpResponse;

    private $container;



    private const ROUTING = ROOT . "App/var/cache/routes/routing.xml";



    public function __construct()
    {
        $this->HttpSession = new HttpSession();
        $this->HttpSession->initSession();


        $this->HttpRequest = new HttpRequest($this->HttpSession);
        $this->HttpResponse = new HttpResponse();

        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        $builder->addDefinitions(self::DEFINITION);
        $this->container = $builder->build();

        $this->resetCache();

        
        if(!file_exists(ROOT . 'App/var/cache/mondata.xml')){
            $buildMonitoring = new BuildDataManager();
            $buildMonitoring->builderManager();
        }


        if(!file_exists(ROOT . "App/var/cache/routes/routing.xml")){
            $buildRouting = new BuildRoutes(ROOT . "App/var/cache/routes/routing.xml");
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

        $container = $this->container();
        
        /*dev*/$vController = null;
        /*dev*/$method = null;
        
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
                    exit;
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

        /*dev*/
            $result = explode('\\', $controller);
            $vController = $result[count($result) - 1];
        /*dev*/

        if(file_exists(ROOT . $path) && !empty($controller)){	
            $controller = $container->get($controller);

            $controller->$action($container->get(Response::class), $this);
            
            return [0 => $controller, 1 => $action, 2 => $vController, 3 => $method];
        }else{
            header('HTTP/1.0 404 Not Found');
            die;
        }

        return null;
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