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

if(isset($_POST['resetCache'])){
	opcache_reset();
	unset($_POST);
}

if(isset($_POST['invalide'])){
	opcache_invalidate($_POST['invalide'], true);
	unset($_POST);
}

if(isset($_POST['resetCacheRoute'])){
	unlink(ROOT . 'App/var/cache/routes/routing.xml');
	unset($_POST);
}

require (ROOT . 'App/vendor/autoload.php');
require (ROOT . 'App/config/config-dev.php');
require (ROOT . 'Tools/bin/tools/time.php');

/*
===================================== Toolbar ==========================================
*/
global  $time, $tree;

$time = new Time();
$time->startController();

$debut = microtime(true);
/*
===================================== Toolbar ==========================================
*/



if(!file_exists(ROOT . "App/var/cache/routes/routing.xml")){
	$buildRouting = new NoMess\Core\BuildRoutes(ROOT . "App/var/cache/routes/routing.xml");
	$buildRouting->build();
}

$request = new NoMess\Core\Request();
$tab = $request->getAction();

if(!is_null($tab)){
	$vController = $tab[2];
	$method = $tab[3];

	$action = $tab[1];
}


require ROOT . 'Tools/bin/tools/toolbar.php';

?>