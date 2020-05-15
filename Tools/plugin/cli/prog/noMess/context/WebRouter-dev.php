<?php

namespace NoMess\Web;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use NoMess\HttpResponse\HttpResponse;

class WebRouter implements ObserverInterface{
	
	private const CONFIG_XML			= 'Web/template.xml';
	private const BASE_ENVIRONMENT		= 'Web/public/';


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
		$file = simplexml_load_file(self::CONFIG_XML);
		
		$path = null;

		$tabData = explode('/', $this->data['stamp']);

		$state = isset($tabData[1]) ? $tabData[1] === '' || $tabData[1] === '0' || $tabData[1] === 'false' ? 'false' : 'true' : 'true';

		foreach($file->template as $value){

			foreach($value->$state as $stamp){
				if(strtolower((string)$stamp) === strtolower($tabData[0])){
					
					$path = (string)$value->attributes()['name'];
					break;
				}
			}
		}	
		

		$param = isset($this->data['attribute']) ? $this->data['attribute'] : null;

		$loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
		$twig = new Environment($loader, [
			'debug' => true,
			'cache' => false,
			'strict_variables' => true
		]);

		$twig->addExtension(new \Twig\Extension\DebugExtension());

		if($path === null){
			throw new \Exception('template.xml: Le template pour ' . $tabData[0] . ' est introuvable   Requête->' . $this->data['stamp']);
		}

		echo $twig->render($path, [
			'WEBROOT' => WEBROOT, 
			'param' => isset($param) ? $param : null, 
			'POST' => isset($_POST) ? $_POST : null, 
			'GET' => isset($_GET) ? $_GET : null, 
			'COOKIE' => $_COOKIE
		]);	
	}

	
	/**
	 * Est informé du changement d'état de Response 
	 *
	 * @return void
	 */
	public function alert(HttpResponse $instance) : void
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
	public function collectData(HttpResponse $instance) : void
	{
		$this->data = json_decode($instance->collectData(), true);
	}
}