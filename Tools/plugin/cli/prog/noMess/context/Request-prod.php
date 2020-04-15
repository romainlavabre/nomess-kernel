<?php

namespace NoMess\Core;

use DI\ContainerBuilder;
use DI\Container;

class Request 
{

    /**
     * Configurer et crée le container
     *
     * @return Container
     */
    private function container() : Container
    {
        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        $builder->addDefinitions(ROOT . 'App/config/di-definitions.php');
        $builder->enableCompilation(ROOT . 'App/var/cache/di'); 
        $builder->writeProxiesToFile(true, ROOT . 'App/var/cache/di');
        return $builder->build();
    }
    
    public function getAction() : ?array
    {

        $container = $this->container();

        
        $file = simplexml_load_file(ROOT . "App/var/cache/routes/routing.xml");

        foreach($file->routes as $value){
            if((string)$value->attributes()['url'] === $_GET['p']){
                $controller = (string)$value->controller;
                
                if(isset($_POST) && !empty($_POST)){
                    $action = (string)$value->post->attributes()['action'];
                }else{
                    $action = (string)$value->get->attributes()['action'];
                }
                
                break;
            }
        } 

        if(file_exists(ROOT . "App/src/Controllers/" . ucfirst($controller) . ".php")){	
            $controller = $container->get("App\\Controllers\\" . ucfirst($controller));
            $controller->$action();
        }

        return null;
    }
}