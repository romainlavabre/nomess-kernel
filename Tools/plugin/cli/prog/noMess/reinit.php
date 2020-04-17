<?php
require 'function-Installer.php';

$comfirme = rdl("Êtes vous certain de vouloir réinitialiser la configuration ? [oui: o | non: Enter]: ");

$api = '../App/';
$source = '../vendor/nomess/kernel/Tools/';


if(!is_null($comfirme)){
	$tabCopyFile = array(
			$source . 'plugin/cli/prog/noMess/file/database.php' => $api . 'config/database.php',
			$source . 'plugin/cli/prog/noMess/file/datacenter.php' => $api . 'config/datacenter.php',
			$source . 'plugin/cli/prog/noMess/file/di-definitions.php' => $api . 'config/di-definitions.php',
			$source . 'plugin/cli/prog/noMess/file/error.php' => $api . 'config/error.php',
			$source . 'plugin/cli/prog/noMess/file/log.txt' => $api . 'var/log/log.txt',
			$source . 'plugin/cli/prog/noMess/file/error.log' => $api . 'var/log/error.log',
	);

	foreach($tabCopyFile as $key => $value){
		$tabFile = explode("/", $key);
		$tabLength = count($tabFile);

		if(@copy($key, $value)){
			echo "Fichier " . $tabFile[$tabLength - 1] . " réinitialisé\n";
		}else{
			echo "Error: Le fichier " . $tabFile[$tabLength - 1] . " n'a pas pu être créé\n";
			$error[] = "Le fichier " . $tabFile[$tabLength - 1] . " n'a pas pu être créé\n";
		}

		usleep(100000);
	}
}
