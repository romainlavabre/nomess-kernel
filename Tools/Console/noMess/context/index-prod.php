<?php
declare(strict_types=1);

ini_set('display_errors', 'off');

define('ROOT', str_replace('public/index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('NOMESS_CONTEXT', 'DEV');
ini_set('error_log', ROOT .'var/log/error.log');


require (ROOT . 'vendor/autoload.php');
require (ROOT . 'vendor/nomess/kernel/Exception/NomessException.php');

return (new Nomess\Initiator\Initiator())->initializer();
