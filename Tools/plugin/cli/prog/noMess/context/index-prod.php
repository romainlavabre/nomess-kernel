<?php
ini_set('display_errors', 'off');

define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

ini_set('error_log', ROOT .'App/var/log/error.log');


require ('vendor/autoload.php');
require (ROOT . 'vendor/nomess/kernel/Exception/WorkException.php');

$route = new NoMess\Router\Router();
$tab = $route->getRoute();
