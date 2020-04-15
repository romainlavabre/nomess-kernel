<?php
ini_set('display_errors', 'off');

define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

ini_set('error_log', ROOT .'App/var/log/error.log');


require (ROOT . 'App/vendor/autoload.php');
require (ROOT . 'App/config/config-prod.php');


if(!file_exists(ROOT . "App/var/cache/routes/routing.xml")){
	$buildRouting = new NoMess\Core\BuildRoutes(ROOT . "App/var/cache/routes/routing.xml");
	$buildRouting->build();
}

$request = new NoMess\Core\Request();
$request->getAction();


?>


