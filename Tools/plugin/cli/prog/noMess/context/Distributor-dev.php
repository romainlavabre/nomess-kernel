<?php

namespace NoMess\Manager;

use NoMess\Components\LightPersists\LightPersists;
use NoMess\Container\Container;
use NoMess\HttpRequest\HttpRequest;
use NoMess\HttpResponse\HttpResponse;
use NoMess\ObserverInterface;
use NoMess\SubjectInterface;
use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Distributor implements SubjectInterface
{

    private const COMPONENT_CONFIGURATION           = ROOT . 'App/config/components.php';

    /**
     * Twig
     */
    private const BASE_ENVIRONMENT = 'public';


    /**
     * Persiste data for redirect
     */
    private const SESSION_DATA = 'nomess_persiste_data';

    /**
     * Data type
     */
    const DEFAULT_DATA = 'php';
    const JSON_DATA = 'json';


    private const SESSION_NOMESS_SCURITY = 'nomess_session_security';
    private const SESSION_NOMESS_TOOLBAR = 'nomess_toolbar';

    /**
     * Template engine
     *
     * @var mixed
     */
    private $engine;

    private HttpRequest $request;

    private HttpResponse $response;


    /**
     * @Inject
     */
    protected Container $container;


    /**
     *
     * @var ObserverInterface
     */
    private ?array $observer = array();

    private ?array $data;


    /**
     * forward data and pending operation
     *
     *
     * @param HttpRequest|null $request
     * @param HttpResponse|null $response
     * @param string $dataType
     *
     * @return Distributor
     */
    public final function forward(?HttpRequest $request, ?HttpResponse $response, string $dataType = self::DEFAULT_DATA): Distributor
    {

        if ($request !== null) {
            $this->request = clone $request;
            $this->data = $request->getData();

            //Intégration des données de lightPersists
            $lpData = array();

            try {
                $lpData = $this->container->get(LightPersists::class)->get(NULL);
            } catch (Throwable $e) {
            }

            $dataSession = null;

            if (isset($lpData)) {
                $dataSession = array_merge($_SESSION, $lpData);
            } else {
                $dataSession = $_SESSION;
            }

            unset($dataSession[self::SESSION_NOMESS_SCURITY]);
            unset($dataSession[self::SESSION_NOMESS_TOOLBAR]);
            $this->data = array_merge($this->data, $dataSession);

            if ($dataType === 'json') {
                $this->data = json_encode($this->data);
            }
        }

        if ($response !== null) {
            $this->response = clone $response;
            $this->response->manage();
        }

        return $this;
    }


    /**
     *
     * Redirect toward an local ressource, if forward method is called, the pending operation
     * will be executed and the data will be presente in next context
     *
     * @param string $url
     * @return Distributor
     */
    public final function redirectLocal(string $url): Distributor
    {
        $this->close();


        if (!empty($this->data)) {
            $_SESSION[self::SESSION_DATA] = $this->data;
        }

        header('Location:' . WEBROOT . $url);

        return $this;
    }


    /**
     * Redirect toward an local ressource, if forward method is called, the pending operation
     * will be executed
     *
     * @param string $url
     * @return Distributor
     */
    public final function redirectOutside(string $url): Distributor
    {
        $this->close();


        header("Location: $url");

        return $this;
    }


    /**
     * Bind the template engine to the response
     *
     * @param string $template
     * @return Distributor
     */
    public final function bindTwig(string $template): Distributor
    {
        $this->close();


        global $time;
        $time->setXdebug(xdebug_time_index());

        $loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
        $this->engine = new Environment($loader, [
            'debug' => true,
            'cache' => false,
            'strict_variables' => true
        ]);

        $this->engine->addExtension(new \Twig\Extension\DebugExtension());

        echo $this->engine->render($template, [
            'URL' => URL,
            'WEBROOT' => WEBROOT,
            'param' => $this->data,
            'POST' => $this->request->getPost(true),
            'GET' => $this->request->getGet(true),
        ]);

        $this->getDevToolbar();

        return $this;
    }


    /**
     * Bind an php file to the response
     *
     * @param string $template
     * @return Distributor
     */
    public final function bindDefaultEngine(string $template): Distributor
    {
        $this->close();


        global $time;
        $time->setXdebug(xdebug_time_index());

        $param = $this->data;

        require(ROOT . 'Web/public/' . $template);

        $this->getDevToolbar();

        return $this;
    }


    /**
     * Return an status code
     *
     * @param int $code
     */
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


    /**
     * Return data
     *
     * @return array|null
     */
    public final function sendData(): ?array
    {
        $this->close();

        return $this->data;
    }


    /**
     * Kill the current process
     */
    public function stopProcess(): void
    {
        die;
    }


    private function getDevToolbar(): void
    {
        global $vController, $method, $action;
        $vController = $_SESSION['nomess_toolbar'][2];
        $method = $_SESSION['nomess_toolbar'][3];

        $action = $_SESSION['nomess_toolbar'][1];

        unset($_SESSION['nomess_toolbar']);

        require_once ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';
    }


    /**
     * Attach observer
     */
    public function attach(): void
    {
        $componentConfig = require self::COMPONENT_CONFIGURATION;

        if ($componentConfig !== null) {
            foreach ($componentConfig as $key => $value) {
                if ($value !== false && isset(class_implements($key)[ObserverInterface::class])) {
                    $this->observer[] = $this->container->get($key);
                }
            }
        }
    }


    /**
     * Notify the observer to change state
     */
    public function notify(): void
    {
        foreach ($this->observer as $value) {
            $value->notifiedOutput();
        }
    }

    private function close(): void
    {
        $this->attach();
        $this->notify();
    }
}