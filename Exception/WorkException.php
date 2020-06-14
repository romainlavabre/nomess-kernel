<?php
namespace NoMess\Exception;


use NoMess\Service\Helpers\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class WorkException extends \ErrorException{

    use Response;

    public function __toString() {
        switch ($this->severity) {
            case E_USER_ERROR : // Si l'utilisateur émet une erreur fatale.
                $type = 'Erreur fatale';
                break;

            case E_WARNING : // Si PHP émet une alerte.
            case E_USER_WARNING : // Si l'utilisateur émet une alerte.
                $type = 'Attention';
                break;

            case E_NOTICE : // Si PHP émet une notice.
            case E_USER_NOTICE : // Si l'utilisateur émet une notice.
                $type = 'Note';
                break;

            default : // Erreur inconnue.
                $type = 'Erreur inconnue';
                break;
        }


        if(NOMESS_CONTEXT === 'DEV') {
            return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> à la ligne <strong>' . $this->line . '</strong><br>';
        }else{
            $this->launchResponse();
        }

    }

    public function launchResponse(): void
    {
        $this->response(500);
    }

}

function error2exception($code, $message, $fichier, $ligne) {
    file_put_contents(ROOT . 'App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "] " . $code . ": " . $message . "\n line " . $ligne . " in " . $fichier . "\n---------------------------------------------------------\n", FILE_APPEND);
    throw new WorkException($message, 0, $code, $fichier, $ligne);
}

function customException($e) {

    if(NOMESS_CONTEXT === 'DEV') {
        require ROOT . 'vendor/nomess/kernel/Tools/Exception/exception.php';

        global $time;
        $time->setXdebug(xdebug_time_index());

        global $vController, $method, $action;

        if (isset($_SESSION['nomess_toolbar'])) {
            $vController = $_SESSION['nomess_toolbar']['controller'];
            $method = $_SESSION['nomess_toolbar']['method'];

            $action = $_SESSION['nomess_toolbar']['action'];

            unset($_SESSION['nomess_toolbar']);


        }

        require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';
    }else{

        http_response_code(500);

        $tabError = require ROOT . 'App/config/error.php';

        if(strpos($tabError[500], '.twig')){
            if(file_exists(ROOT . 'Web/public/' . $tabError[500])) {
                bindTwig($tabError[500]);
            }
        }else{
            if(file_exists(ROOT . $tabError[500])) {
                include(ROOT . $tabError[500]);
            }
        }
        die;
    }


    file_put_contents(ROOT . 'App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "]Line " . $e->getLine() . ": " . $e->getFile() . "\nException: " . $e->getMessage() . "\n---------------------------------------------------------\n", FILE_APPEND);
}

set_error_handler('NoMess\Exception\error2exception');
set_exception_handler('NoMess\Exception\customException');


function bindTwig(string $template) : void
{
    $loader = new FilesystemLoader('public');
    $engine = new Environment($loader, [
        'cache' => false,
    ]);

    $engine->addExtension(new \Twig\Extension\DebugExtension());

    echo $engine->render($template, [
        'URL' => URL,
        'WEBROOT' => WEBROOT
    ]);
}