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
	file_put_contents('App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "] " . $code . ": " . $message . "\n line" . $ligne . " dans " . $fichier . "\n---------------------------------------------------------\n", FILE_APPEND);
	throw new WorkException($message, 0, $code, $fichier, $ligne);
}

function customException($e) {
	$array = '<table class="table">
				<tr>
					<th style="width: 1%;">Floor</th>
					<th>File</th>
					<th>Line</th>
					<th>Function</th>
				</tr>';

	$iter = count($e->getTrace());


	for($i = 0; $i < $iter; $i++){

		$traceC = (isset($e->getTrace()['class'])) ? $e->getTrace()['class'] : null;
		$traceF = (isset($e->getTrace()['function'])) ? $e->getTrace()['function'] : null;
		$file = isset($e->getTrace()[$i]['file']) ? $e->getTrace()[$i]['file'] : null;
		$line = isset($e->getTrace()[$i]['line']) ? $e->getTrace()[$i]['line'] : null;

		$array .= '
		<tr>
			<td style="text-align: center; width: 1%" >' . $i . '</td>
			<td>' . str_replace(ROOT, '', $file) . '</td>
			<td>' . $line . '</td>
			<td>' . $traceC . '::' . $traceF . '</td>
		</tr>';
	}

	$array .= '</table>';

	echo "
				<style type='text/css'>
					body{ background: #599954; color: white; font-family: sans-serif;}
					.trace{ font-size: 25px;  background: #333; padding: 5px; box-shadow: 2px 2px 5px 5px #333; }

					.table, .table *{
						border: 1px solid grey;
						width: 100%;
						font-size: 25px;
						border-collapse: collapse;
					}

					.table td, .table th{ padding: 5px;}
				</style>

				<span style='font-size: 30px'>
				Ligne " . $e->getLine() . " dans " . str_replace(ROOT, '', $e->getFile()) . 
				"<br><br>
				<strong>NoMessException</strong> : " . $e->getMessage() . "
				</span>
				<br>
				<br>
				<br>
				<div class='trace'>
				Trace:
				" . $array . "
				</div>
				<br>
				<br>


		";

		global $time;
		$time->setXdebug(xdebug_time_index());

		global $vController, $method, $action;
		
		if(isset($_SESSION['nomess_toolbar'])){
			$vController = $_SESSION['nomess_toolbar'][2];
			$method = $_SESSION['nomess_toolbar'][3];

			$action = $_SESSION['nomess_toolbar'][1];

			unset($_SESSION['nomess_toolbar']);
		
			require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';
		}
	
	file_put_contents('App/var/log/log.txt', "[" . date('d/m/Y H:i:s') . "]Line " . $e->getLine() . ": " . $e->getFile() . "\nException: " . $e->getMessage() . "\n---------------------------------------------------------\n", FILE_APPEND);
}

set_error_handler('NoMess\Exception\error2exception');
set_exception_handler('NoMess\Exception\customException');
