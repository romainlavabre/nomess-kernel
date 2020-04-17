<?php
/*
	============================================================================
*/
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set("log_errors", "1");

define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

ini_set('error_log', ROOT .'App/var/log/error.log');

/*
	============================================================================
*/


require (ROOT . 'vendor/autoload.php');
require (ROOT . 'vendor/nomess/kernel/Tools/tools/time.php');

/*
===================================== Toolbar ==========================================
*/

global $time;
$time = new Time();
/*
===================================== Toolbar ==========================================
*/


$route = new NoMess\Router\Router();
$tab = $route->getRoute();

if(!is_null($tab)){
	$vController = $tab[2];
	$method = $tab[3];

	$action = $tab[1];
}


require ROOT . 'vendor/nomess/kernel/Tools/tools/toolbar.php';

?>