<?php
require 'function-Installer.php';

$comfirme = rdl("Êtes vous certain de vouloir réinitialiser la configuration ? [oui: o | non: Enter]: ");

$api = '../App/';
if(!is_null($comfirme)){
	$tabCopyFile = array(
			'bin/plugin/cli/prog/noMess/file/config-dev.php' => $api . 'config/config-dev.php',
			'bin/plugin/cli/prog/noMess/file/config-prod.php' => $api . 'config/config-prod.php',
			'bin/plugin/cli/prog/noMess/file/log-dev.txt' => $api . 'var/log/log-dev.txt',
			'bin/plugin/cli/prog/noMess/file/log-prod.txt' => $api . 'var/log/log-prod.txt',
			'bin/plugin/cli/prog/noMess/file/error.log' => $api . 'var/log/error.log',
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
