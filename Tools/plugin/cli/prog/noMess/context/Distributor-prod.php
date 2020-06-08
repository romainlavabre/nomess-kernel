<?php

namespace NoMess\Manager;

use NoMess\Container\Container;
use Throwable;
use Twig\Environment;
use NoMess\SubjectInterface;
use NoMess\ObserverInterface;
use Twig\Loader\FilesystemLoader;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;
use NoMess\Components\LightPersists\LightPersists;

abstract class Distributor implements SubjectInterface
{

    private const COMPONENT_CONFIGURATION           = ROOT . 'App/config/components.php';
    private const BASE_ENVIRONMENT                  = 'public';
    private const CACHE_TWIG                        = ROOT . 'Web/cache/twig/';

    private const SESSION_DATA                      = 'nomess_persiste_data';

    const DEFAULT_DATA                              = 'php';
    const JSON_DATA                                 = 'json';

    private const SESSION_NOMESS_SCURITY            = 'nomess_session_security';

    private $engine;

    private HttpRequest $request;

    private HttpResponse $response;

    /**
     * @Inject
     */
    protected Container $container;

    private ?array $observer = array();

    private ?array $data;

    public final function forward(?HttpRequest $request, ?HttpResponse $response, string $dataType = self::DEFAULT_DATA) : Distributor
    {

        if($request !== null){
            $this->request = clone $request;
            $this->data = $request->getData();

            //Intégration des données de lightPersists
            $lpData = array();

            try{
                $lpData = $this->container->get(LightPersists::class)->get(NULL);
            }catch(Throwable $e){}

            $dataSession = null;

            if(isset($lpData)){
                $dataSession = array_merge($_SESSION, $lpData);
            }else{
                $dataSession = $_SESSION;
            }

            unset($dataSession[self::SESSION_NOMESS_SCURITY]);
            $this->data = array_merge($this->data, $dataSession);
  
            if($dataType === 'json'){
                $this->data = json_encode($this->data);
            }
        }

        if($response !== null){
            $this->response = clone $response;
            $this->response->manage();
        }

        return $this;
    }

    public final function redirectLocal(string $url) : Distributor
    {

        $this->close();

        if(!empty($this->data)){
            $_SESSION[self::SESSION_DATA] = $this->data;
        }

        header('Location:' . WEBROOT . $url);

        return $this;
    }

    public final function redirectOutside(string $url) : Distributor
    {

        $this->close();

        header("Location: $url");

        return $this;
    }


    public final function bindTwig(string $template) : Distributor
    {

        $this->close();


        $cache = self::CACHE_TWIG;

        if(isset($_SESSION['numess_buffer'])){
            $cache = false;
        }

        $loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
		$this->engine = new Environment($loader, [
			'cache' => $cache
		]);

        $this->engine->addExtension(new \Twig\Extension\DebugExtension());
        
        echo $this->engine->render($template, [
            'URL' => URL,
			'WEBROOT' => WEBROOT, 
			'param' => $this->data, 
			'POST' => $this->request->getPost(true), 
			'GET' => $this->request->getGet(true), 
        ]);	
        
        return $this;
    }

    public final function bindDefaultEngine(string $template) : Distributor
    {

        $this->close();


        $param = $this->data;

        require(ROOT . 'Web/public/' . $template);


        return $this;
    }


    public final function statusCode(int $code): void
    {
        http_response_code($code);

        $tabError = require ROOT . 'App/config/error.php';

        if(strpos($tabError[$code], '.twig')){
            if(file_exists(ROOT . 'Web/public/' . $tabError[$code])) {
                $this->bindTwig($tabError[$code]);
            }
        }else{
            if(file_exists(ROOT . $tabError[$code])) {
                include(ROOT . $tabError[$code]);
            }
        }
        die;
    }


    public final function sendData() : ?array
    {
        return $this->data;
    }

    public function stopProcess() : void
    {
        die;
    }

    public function attach() : void
    {
        $componentConfig = require self::COMPONENT_CONFIGURATION;

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
            $value->notifiedOutput();
        }
    }

    private function close() : void
    {
        $this->attach();
        $this->notify();
    }
}