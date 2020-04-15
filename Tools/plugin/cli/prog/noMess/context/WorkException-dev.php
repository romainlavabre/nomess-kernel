<?php
namespace NoMess\Core;


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
		
		require ROOT . 'Tools/bin/tools/toolbar.php';
		
		return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> à la ligne <strong>' . $this->line . '</strong><br>';
	}
}

function error2exception($code, $message, $fichier, $ligne) {
	global $Log;
	file_put_contents($Log, $code . ": " . $message . "\n line" . $ligne . " dans " . $fichier . "\n---------------------------------------------------------\n", FILE_APPEND);
	throw new WorkException($message, 0, $code, $fichier, $ligne);
}

function customException($e) {

	global $Log, $CONTEXT;

	echo '
				"Ligne "' . $e->getLine() . '" dans "' . $e->getFile() . '
				"<br /><strong>Exception lancée</strong> : "' . $e->getMessage() . '"<br>";

		';
	
	require ROOT . 'Tools/bin/tools/toolbar.php';
	
	file_put_contents($Log, "Line " . $e->getLine() . ": " . $e->getFile() . "\nException: " . $e->getMessage() . "\n---------------------------------------------------------\n", FILE_APPEND);
}

set_error_handler('NoMess\Core\error2exception');
set_exception_handler('NoMess\Core\customException');