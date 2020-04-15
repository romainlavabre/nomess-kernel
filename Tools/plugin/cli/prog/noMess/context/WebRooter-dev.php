<?php

namespace Web;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use NoMess\Core\{
	ObserverInterface,
	Response
};

class WebRooter implements ObserverInterface{
	
	/**
	 * Stock les datas
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

		$template = isset($tabData[1]) ? $tabData[1] === 'false' ? 'ft' : 'tt' : 'tt';
		
		foreach($file->template as $value){
			if(strtolower((string)$value->attributes()['stamp']) === strtolower($tabData[0])){
				$path = (string)$value->attributes()[$template];
			}
		}		
		

		$param = isset($this->data['attribut']) ? $this->data['attribut'] : null;

		require_once 'Web/vendor/autoload.php';

		$loader = new FilesystemLoader('Web/public/');
		$twig = new Environment($loader, [
			'debug' => true,
			'cache' => false,
			'strict_variables' => true
		]);
				
		echo $twig->render($path, ['WEBROOT' => WEBROOT, 'param' => $param]);		
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