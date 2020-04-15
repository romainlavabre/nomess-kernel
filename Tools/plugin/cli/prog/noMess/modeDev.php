<?php
echo "Lancement de la configuration...\n";

require 'function-Installer.php';

$comfirme = rdl("Plusieurs fichier vont être remplacé (index.php, WorkException.php, Response.php), continuer ? [oui: o | non: Enter]: ");


$api = '../App/';
if(!is_null($comfirme)){
	$tabCopyFile = array(
			'bin/plugin/cli/prog/noMess/context/Request-dev.php' => $api . 'vendor/NoMess/Request.php',
			'bin/plugin/cli/prog/noMess/context/Response-dev.php' => $api . 'vendor/NoMess/Response.php',
			'bin/plugin/cli/prog/noMess/context/WorkException-dev.php' => $api . 'vendor/NoMess/WorkException.php',
			'bin/plugin/cli/prog/noMess/context/index-dev.php' => '../index.php',
			'bin/plugin/cli/prog/noMess/context/WebRooter-dev.php' => '../Web/WebRooter.php'
	);

	foreach($tabCopyFile as $key => $value){
		$tabFile = explode("/", $key);
		$tabLength = count($tabFile);

		if(@copy($key, $value)){
			echo "Fichier " . $tabFile[$tabLength - 1] . " réinitialisé\n";
		}else{
			echo "Error: Le fichier " . $tabFile[$tabLength - 1] . " n'a pas pu être créé\n";
		}
	}
}