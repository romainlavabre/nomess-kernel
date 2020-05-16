<?php

namespace NoMess\Manager;

use Throwable;
use Twig\Environment;
use NoMess\SubjectInterface;
use NoMess\ObserverInterface;
use Twig\Loader\FilesystemLoader;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;
use Psr\Container\ContainerInterface;
use NoMess\Component\LightPersists\LightPersists;

abstract class Distributor implements SubjectInterface
{

    /**
     * Twig
     */
    private const BASE_ENVIRONMENT          = 'Web/public/';
    private const CACHE_TWIG			    = 'Web/cache/twig/';


    /**
     * Persiste data for redirect
     */
    private const SESSION_DATA              = 'nomess_persiste_data';

    /**
     * Data
     */
    const DEFAULT_DATA                      = 'php';
    const JSON_DATA                         = 'json';


    private const SESSION_NOMESS_SCURITY    = 'nomess_session_security';

    /**
     * Moteur de template
     *
     * @var mixed
     */
    private $engine;

    /**
     * 
     *
     * @var HttpRequest
     */
    private $request;

    /**
     * 
     *
     * @var HttpResponse
     */
    private $response;


    /**
     * 
     * @Inject
     *
     * @var ContainerInterface
     */
    private $container;


    /**
     *
     * @var ObserverInterface
     */
    private $observer = array();


    /**
     *
     * @var array
     */
    private $data;


    /**
     * Fait suivre les données et opération en attente avec la requête
     * 
     *
     * @param HttpRequest|null $request
     * @param HttpResponse|null $response
     * @param string $dataType
     *
     * @return Distributor
     */
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


    /**
     * Redirige vers une resource local, si request et response sont intégré avec forward,
     * Les opération en attente seront exécuté normalement et les données seront présente 
     * dans le contexte de la ressource cible 
     *
     * @param string $url
     *
     * @return Distributor
     */
    public final function redirectLocal(string $url) : Distributor
    {

        $this->close();

        if(!empty($this->data)){
            $_SESSION[self::SESSION_DATA] = $this->data;
        }

        header('Location:' . WEBROOT . $url);

        return $this;
    }


    /**
     * Redirige vers une ressource externe, si response est intégré avec forward, 
     * les opération en attente seront exécuté normalement (conseillé), l'introduction 
     * de request n'aura aucun impact et ses données seront perdu
     *
     * @param string $url
     *
     * @return Distributor
     */
    public final function redirectOutside(string $url) : Distributor
    {

        $this->close();

        header("Location: $url");

        return $this;
    }


    /**
     * Lie le moteur twig à la response
     *
     * @param string $template
     *
     * @return Distributor
     */
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
			'WEBROOT' => WEBROOT, 
			'param' => $this->data, 
			'POST' => $this->request->getPost(true), 
			'GET' => $this->request->getGet(true), 
        ]);	
        
        return $this;
    }


    /**
     * Lie un fichier php à la response
     *
     * @param string $template
     *
     * @return Distributor
     */
    public final function bindDefaultEngine(string $template) : Distributor
    {

        $this->close();


        $param = $this->data;

        require(ROOT . 'Web/public/' . $template);


        return $this;
    }


    /**
     * Retourne les données
     *
     * @return array|null
     */
    public final function sendData() : ?array
    {
        return $this->data;
    }



    /**
     * Tue le procéssus courant
     *
     * @return void
     */
    public function stopProcess() : void
    {
        die;
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
            $value->notifiedOutput();
        }
    }

    private function close() : void
    {
        $this->attach();
        $this->notify();
    }
}