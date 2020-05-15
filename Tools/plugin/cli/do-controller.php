<?php

require 'function-Installer.php';

$generate  = new Controller();
$generate->generator();

class Controller 
{

    /**
     * Chemin du fichier
     *
     * @var string
     */
    private $dir;

    /**
     * Nom du controller
     *
     * @var string
     */
    private $controller;

    /**
     * Route
     *
     * @var string
     */
    private $route;


    public function generator() : void
    {
        $dir = rdl("Depuis 'App/src/Controller/', précisez le chemin (vide pour racine): ");

        if($dir !== null){
            $tabDir = explode('/', $dir);

            $dir = null;

            foreach($tabDir as $value){
                $dir = $dir . $value . '/';
            }

            $this->dir = $dir;
        }

        do{
            $this->controller = rdl("Précisez le nom du controller: ");
            $this->route = rdl("Précisez la route: ");

            file_put_contents('../App/src/Controllers/' . $this->dir . ucfirst($this->controller) . '.php', $this->getContent());

            $restart = rdl("Continuer ? [N: n | O: Enter");

            if($restart === null){
                $restart = true;
            }else{
                $restart = false;
            }
        }while($restart === true);
    }

    private function getNamespace() : string
    {

        $namespace = 'App\Controllers';

        $tabDir = explode('/', $this->dir);

        foreach($tabDir as $value){
            if(!empty($value)){
                $namespace = $namespace . '\\' . ucfirst($value);
            }
        }

        return $namespace;
    }


    private function getContent() : string
    {
        $content = "<?php

namespace " . $this->getNamespace() . ";

use NoMess\HttpResponse\HttpResponse;
use NoMess\HttpRequest\HttpRequest;
use NoMess\Manager\Distributor;


/**
 * @Route{\"" . $this->route . "\"} 
 */
class " . ucfirst($this->controller) . " extends Distributor
{


    /**
     * @Inject
     * 
     * @var YourInstance
     */
    private \$yourInstance;

    /**
     * 
     * @param HttpRequest \$request
     * @param HttpResponse \$response
     * @return void
     */
    public function doGet(HttpResponse \$response, HttpRequest \$request) : void
    {
        \$this->forward(\$request, \$response)->bindTwig('template');
    }

    /**
     *
     * @param HttpRequest \$request
     * @param HttpResponse \$response
     * @return void
     */
    public function doPost(HttpResponse \$response, HttpRequest \$request) : void
    {

        \$tracker = \$this->yourInstance->service(\$request);

        \$this->forward(\$request, \$response)->bindTwig('template');
    }
}
        ";

        return $content;
    }
}