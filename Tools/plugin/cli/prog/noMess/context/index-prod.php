<?php
ini_set('display_errors', 'off');

define('ROOT', str_replace('Web/index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('WEBROOT', 'public/');
define('NOMESS_CONTEXT', 'PROD');

ini_set('error_log', ROOT .'App/var/log/error.log');


require (ROOT . 'vendor/autoload.php');
require (ROOT . 'vendor/nomess/kernel/Exception/WorkException.php');

$route = new NoMess\Router\Router();
$tab = $route->getRoute();
