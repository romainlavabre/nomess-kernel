<?php
echo "Lancement de la configuration...\n";

require 'function-Installer.php';

$comfirme = rdl("Plusieurs fichier vont être remplacé, continuer ? [oui: o | non: Enter]: ");


$api = '../vendor/NoMess/';
if(!is_null($comfirme)){
	$tabCopyFile = array(
			'bin/plugin/cli/prog/noMess/context/Request-dev.php' => $api . 'HttpRequest/HttpRequest.php',
			'bin/plugin/cli/prog/noMess/context/Response-dev.php' => $api . 'HttpResponse/HttpResponse.php',
			'bin/plugin/cli/prog/noMess/context/index-dev.php' => '../index.php',
			'bin/plugin/cli/prog/noMess/context/WebRooter-dev.php' => 'Web/WebRouter.php'
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