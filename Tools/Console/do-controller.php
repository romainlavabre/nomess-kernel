<?php

require 'function-Installer.php';

$generate  = new Controller();
$generate->generator();

class Controller 
{

    /**
     * Path of file
     *
     * @var string
     */
    private $dir;

    /**
     * Controller name
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
        $dir = rdl("Precise path beginning 'App/src/Controller/', void for racine: ");

        if($dir !== null){
            $tabDir = explode('/', $dir);

            $dir = null;

            foreach($tabDir as $value){
                $dir = $dir . $value . '/';
            }

            $this->dir = $dir;
        }

        do{
            $this->controller = rdl("Precise name of controller: ");
            $this->route = rdl("Precise route: ");

            file_put_contents('App/src/Controllers/' . $this->dir . ucfirst($this->controller) . '.php', $this->getContent());

            $restart = rdl("Pursue ? [N: n | O: Enter] ");

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

use NoMess\Http\HttpResponse;
use NoMess\Http\HttpRequest;
use NoMess\Manager\Distributor;


/**
 * @Route{\"" . $this->route . "\"} 
 * @autowire
 */
class " . ucfirst($this->controller) . " extends Distributor
{

    private const WEB_TEMPLATE              = 'Web_template';


    /**
     * @Inject
     */
    private TypeOfInstance \$yourInstance;

    /**
     * 
     * @param HttpRequest \$request
     * @param HttpResponse \$response
     */
    public function doGet(HttpResponse \$response, HttpRequest \$request): void
    {
        \$this->forward(\$request, \$response)->bindTwig(self::WEB_TEMPLATE)->stopProcess();
    }

    /**
     *
     * @param HttpRequest \$request
     * @param HttpResponse \$response
     */
    public function doPost(HttpResponse \$response, HttpRequest \$request): void
    {

        \$tracker = \$this->yourInstance->service(\$request);

        \$this->forward(\$request, \$response)->bindTwig(self::WEB_TEMPLATE)->stopProcess();
    }
}
        ";

        return $content;
    }
}