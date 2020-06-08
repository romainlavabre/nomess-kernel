<?php
namespace NoMess\Exception;


class WorkException extends \ErrorException{

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

        require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';

        return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> à la ligne <strong>' . $this->line . '</strong><br>';
    }
}

function error2exception($code, $message, $fichier, $ligne) {
    file_put_contents(ROOT . 'App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "] " . $code . ": " . $message . "\n line" . $ligne . " dans " . $fichier . "\n---------------------------------------------------------\n", FILE_APPEND);
    throw new WorkException($message, 0, $code, $fichier, $ligne);
}

function customException($e) {


    require ROOT . 'vendor/nomess/kernel/Tools/Exception/exception.php';

    global $time;
    $time->setXdebug(xdebug_time_index());

    global $vController, $method, $action;

    if(isset($_SESSION['nomess_toolbar'])){
        $vController = $_SESSION['nomess_toolbar'][2];
        $method = $_SESSION['nomess_toolbar'][3];

        $action = $_SESSION['nomess_toolbar'][1];

        unset($_SESSION['nomess_toolbar']);


    }

    require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';

    file_put_contents(ROOT . 'App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "]Line " . $e->getLine() . ": " . $e->getFile() . "\nException: " . $e->getMessage() . "\n---------------------------------------------------------\n", FILE_APPEND);
}

set_error_handler('NoMess\Exception\error2exception');
set_exception_handler('NoMess\Exception\customException');
