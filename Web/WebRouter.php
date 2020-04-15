<?php

namespace NoMess\Web;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use NoMess\HttpResponse\{
	ObserverInterface,
	Response
};

class WebRouter implements ObserverInterface{
	
	/**
	 * Stock les data
	 *
	 * @var array
	 */
	private $data;


	/**
	 * Aiguilleur pour la construction de la vue
	 * 		-> Lis la configuration
	 * 		-> Récupère les modules
	 * 		-> Valide les paramêtres
	 *
	 * @return void
	 */
	public function buildView() : void
 	{
		$file = simplexml_load_file('Web/config.xml');
		
		$path = null;

		$tabData = explode('/', $this->data['stamp']);

		$template = isset($tabData[1]) ? $tabData[1] === '' || $tabData[1] === '0' || $tabData[1] === 'false' ? 'ft' : 'tt' : 'tt';

		foreach($file->template as $value){
			if(strtolower((string)$value->attributes()['stamp']) === strtolower($tabData[0])){
				
				$path = (string)$value->attributes()[$template];
			}
		}		
		

		$param = isset($this->data['attribute']) ? $this->data['attribute'] : null;

		$loader = new FilesystemLoader('Web/public/');
		$twig = new Environment($loader, [
			'debug' => true,
			'cache' => false,
			'strict_variables' => true
		]);
		$twig->addExtension(new \Twig\Extension\DebugExtension());

		if($path === null){
			throw new \Exception('Config.xml: Le template pour ' . $tabData[0] . ' est introuvable   Requête->' . $this->data['stamp']);
		}

		echo $twig->render($path, ['WEBROOT' => WEBROOT, 'param' => isset($param) ? $param : null, 'POST' => isset($_POST) ? $_POST : null, 'GET' => isset($_GET) ? $_GET : null]);		
	}

	
	/**
	 * Est informé du changement d'état de Response 
	 *
	 * @return void
	 */
	public function alert(Response $instance) : void
	{
		$this->collectData($instance);
		$this->buildView();
	}

	/**
	 * Récupère les données formatté
	 *
	 * @param Response $instance
	 * @return void
	 */
	public function collectData(Response $instance) : void
	{
		$this->data = json_decode($instance->collectData(), true);
	}
}