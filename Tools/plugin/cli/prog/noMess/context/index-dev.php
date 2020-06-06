<?php
/*
	============================================================================
*/
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set("log_errors", "1");

define('ROOT', str_replace('Web/index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('WEBROOT', str_replace('index.php', 'public/', $_SERVER['SCRIPT_NAME']));
define('URL', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('NOMESS_CONTEXT', 'DEV');

ini_set('error_log', ROOT .'App/var/log/error.log');

/*
	============================================================================
*/


require (ROOT . 'vendor/autoload.php');
require (ROOT . 'vendor/nomess/kernel/Exception/WorkException.php');
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
$route->getRoute();


?>