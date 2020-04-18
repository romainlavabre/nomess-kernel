<?php

namespace NoMess\Router;

use DI\ContainerBuilder;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpSession\HttpSession;
use NoMess\HttpResponse\HttpResponse;
use NoMess\Router\Builder\BuildRoutes;
use NoMess\DataManager\Builder\BuilderDataManager;

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
        $builder->enableCompilation(ROOT . 'App/var/cache/di'); 
        $builder->writeProxiesToFile(true, ROOT . 'App/var/cache/di');
        $this->container = $builder->build();

        $this->HttpSession = $this->container->get(HttpSession::class);
        $this->HttpRequest = $this->container->get(HttpRequest::class);
        $this->HttpResponse = $this->container->get(HttpResponse::class);

        $this->HttpSession->initSession();

        
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
                }else{
                    $action = 'doGet';
                }
                
                break;
            }
        } 

        if(file_exists($path) && !empty($controller)){	
            $controller = $this->container->get($controller);

            $controller->$action($this->HttpResponse, $this->HttpRequest);
        }else{

            $tabError = require ROOT . 'App/config/error.php';

            include(ROOT . $tabError['404']);
            die;
        }

    }
}