<?php

namespace NoMess\Manager;

use NoMess\Components\Forms\FormAccess;
use NoMess\Components\LightPersists\LightPersists;
use NoMess\Container\Container;
use NoMess\Exception\WorkException;
use NoMess\Http\HttpRequest;
use NoMess\Http\HttpResponse;
use NoMess\ObserverInterface;
use NoMess\Service\Helpers\Response;
use NoMess\SubjectInterface;
use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Distributor implements SubjectInterface
{

    use Response;

    private const COMPONENT_CONFIGURATION           = ROOT . 'App/config/components.php';

    private const BASE_ENVIRONMENT                  = 'public';
    private const CACHE_TWIG                        = ROOT . 'Web/cache/twig/';
    private const SESSION_DATA                      = 'nomess_persiste_data';

    const DEFAULT_DATA                              = 'php';
    const JSON_DATA                                 = 'json';


    private const SESSION_NOMESS_SCURITY            = 'nomess_session_security';
    private const SESSION_NOMESS_TOOLBAR            = 'nomess_toolbar';

    private $engine;
    private HttpRequest $request;
    private HttpResponse $response;
    private ?array $form;


    /**
     * @Inject
     */
    protected Container $container;

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
    protected final function forward(?HttpRequest $request, ?HttpResponse $response, string $dataType = self::DEFAULT_DATA): Distributor
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

            if(NOMESS_CONTEXT === 'DEV') {
                unset($dataSession[self::SESSION_NOMESS_TOOLBAR]);
            }

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
     * Redirects to a local resource, if the forward method is called, pending operations
     * will be executed and the data will be presented in the following context
     *
     * @param string $url
     * @return Distributor
     */
    protected final function redirectLocal(string $url): Distributor
    {
        $this->close();


        if (!empty($this->data)) {
            $_SESSION[self::SESSION_DATA] = $this->data;
        }

        header('Location:' . WEBROOT . $url);

        return $this;
    }


    /**
     * Redirects to an external resource, if the forward method is called, pending operations will be executed
     *
     * @param string $url
     * @return Distributor
     */
    protected final function redirectOutside(string $url): Distributor
    {
        $this->close();


        header("Location: $url");

        return $this;
    }


    /**
     * Return an status code
     *
     * @param int $code
     */
    protected final function statusCode(int $code): void
    {
        $this->response($code);
    }


    /**
     * Binds the twig model engine to the response
     *
     * @param string $template
     * @return Distributor
     */
    protected final function bindTwig(string $template): Distributor
    {
        $this->close();


        if(NOMESS_CONTEXT === 'DEV') {
            global $time;
            $time->setXdebug(xdebug_time_index());
        }

        $loader = new FilesystemLoader(self::BASE_ENVIRONMENT);

        if(NOMESS_CONTEXT === 'DEV') {
            $this->engine = new Environment($loader, [
                'debug' => true,
                'cache' => false,
                'strict_variables' => true
            ]);
        }else{
            $this->engine = new Environment($loader, [
                'cache' => self::CACHE_TWIG
            ]);
        }

        $this->engine->addExtension(new \Twig\Extension\DebugExtension());

        echo $this->engine->render($template, [
            'URL' => URL,
            'WEBROOT' => WEBROOT,
            'POST' => $this->request->getPost(true),
            'GET' => $this->request->getGet(true),
            'FORM' => isset($this->form) ? $this->form : null,
            'param' => $this->data,
            '_token' => isset($_SESSION[getenv('NM_TOKEN_NAME_CSRF')]) ? $_SESSION[getenv('NM_TOKEN_NAME_CSRF')] : null
        ]);

        if (NOMESS_CONTEXT === 'DEV') {
            $this->getDevToolbar();
        }

        return $this;
    }


    /**
     * Binds a php file to the response
     *
     * @param string $template
     * @return Distributor
     */
    protected final function bindDefaultEngine(string $template): Distributor
    {
        $this->close();


        if(NOMESS_CONTEXT === 'DEV') {
            global $time;
            $time->setXdebug(xdebug_time_index());
        }

        $param = $this->data;

        echo require(ROOT . 'Web/public/' . $template);

        if(NOMESS_CONTEXT === 'DEV') {
            $this->getDevToolbar();
        }

        return $this;
    }


    /**
     * Bind one or many form
     *
     * @param array $form
     * @return Distributor
     * @throws WorkException
     */
    protected final function bindForm(array $form): Distributor
    {
        foreach ($form as $name){
            $formAccess = new FormAccess();
            $this->form[$name] = $formAccess->get($name);
        }

        return $this;
    }


    /**
     * Return data
     *
     * @return array|null
     */
    protected final function sendData(): ?array
    {
        $this->close();

        return $this->data;
    }


    /**
     * Kill the current process
     */
    protected function stopProcess(): void
    {
        die;
    }


    private function getDevToolbar(): void
    {
        global $vController, $method, $action;
        $vController = $_SESSION['nomess_toolbar']['controller'];
        $method = $_SESSION['nomess_toolbar']['method'];

        $action = $_SESSION['nomess_toolbar']['action'];

        unset($_SESSION['nomess_toolbar']);

        require_once ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';
    }


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
