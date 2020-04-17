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
use NoMess\Manager\ControllerManager;


/**
 * @Route{\"" . $this->route . "\"} 
 */
class " . ucfirst($this->controller) . " extends ControllerManager
{

    private const STAMP_GET     = '" . str_replace('/', '\\', $this->dir) . ucfirst($this->controller) . ":doGet';
    private const STAMP_POST    = '" . str_replace('/', '\\', $this->dir) . ucfirst($this->controller) . ":doPost/';

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
        \$response->render([
            'stamp' => self::STAMP_GET
        ]);
    }

    /**
     *
     * @param HttpRequest \$request
     * @param HttpResponse \$response
     * @return void
     */
    public function doPost(HttpResponse \$response, HttpRequest \$request) : void
    {
        \$data = \$request->getParameters();

        \$report = \$this->yourInstance->service(\$data);

        \$response->render([
            'stamp' => self::STAMP_POST . \$report
        ]);
    }
}
        ";

        return $content;
    }
}