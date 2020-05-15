<?php
namespace NoMess\Exception;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


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
		
		header('HTTP/1.0 500 INTERNAL SERVER ERROR');

        $tabError = require ROOT . 'App/config/error.php';

        if(strpos($tabError['500'], '.twig')){
			bindTwig($tabError['500']);
		}else{
			include(ROOT . $tabError['500']);
		}
        die;
	}
}

function error2exception($code, $message, $fichier, $ligne) {
	file_put_contents('App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "] " . $code . ": " . $message . "\n line" . $ligne . " dans " . $fichier . "\n---------------------------------------------------------\n", FILE_APPEND);
	throw new WorkException($message, 0, $code, $fichier, $ligne);
}

function customException($e) {

	file_put_contents('App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "]Line " . $e->getLine() . ": " . $e->getFile() . "\nException: " . $e->getMessage() . "\n---------------------------------------------------------\n", FILE_APPEND);

	header('HTTP/1.0 500 INTERNAL SERVER ERROR');

    $tabError = require ROOT . 'App/config/error.php';

    if(strpos($tabError['500'], '.twig')){
		bindTwig($tabError['500']);
	}else{
		include(ROOT . $tabError['500']);
	}
    die;
	
}

set_error_handler('NoMess\Exception\error2exception');
set_exception_handler('NoMess\Exception\customException');

/**
 * Charge une erreur avec twig
 *
 * @param string $template
 *
 * @return void
 */
function bindTwig(string $template) : void
{
	$loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
	$this->engine = new Environment($loader, [
		'cache' => false,
	]);

	$this->engine->addExtension(new \Twig\Extension\DebugExtension());
	
	echo $this->engine->render($template, [
		'WEBROOT' => WEBROOT
	]);	
}