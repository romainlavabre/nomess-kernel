<?php

namespace NoMess\Web;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use NoMess\Web\Builder\WebBuilder;
use NoMess\HttpResponse\HttpResponse;

class WebRouter implements ObserverInterface{
	
	private const CACHE_TWIG			= 'Web/cache/twig/';
	private const CACHE_WEBROUTER		= 'Web/cache/webRouter/template.php';

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
		$tabData = explode('/', $this->data['stamp']);

		$state = isset($tabData[1]) ? $tabData[1] === '' || $tabData[1] === '0' || $tabData[1] === '/false' ? '/false' : '/true' : '/true';
	

		if(!file_exists(self::CACHE_WEBROUTER)){
			$builder = new WebBuilder();
			$builder->webBuilder();
		}

		$route = require_once self::CACHE_WEBROUTER;
		

		$param = isset($this->data['attribute']) ? $this->data['attribute'] : null;

		$loader = new FilesystemLoader(self::BASE_ENVIRONMENT);
		$twig = new Environment($loader, [
			'cache' => self::CACHE_TWIG,
		]);

		if(!isset($route[strtolower($tabData[0]) . $state])){
			throw new \Exception('template.xml: Le template pour ' . $tabData[0] . ' est introuvable   Requête->' . $this->data['stamp']);
		}

		echo $twig->render($route[strtolower($tabData[0]) . $state], [
			'WEBROOT' => WEBROOT, 
			'param' => isset($param) ? $param : null, 
			'POST' => isset($_POST) ? $_POST : null, 
			'GET' => isset($_GET) ? $_GET : null, 
			'COOKIE' => $_COOKIE
		]);	
	}

	
	/**
	 * Est informé du changement d'état de HttpResponse 
	 *
	 * @param HttpResponse $instance
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