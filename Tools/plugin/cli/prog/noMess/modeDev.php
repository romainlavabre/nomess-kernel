<?php
echo "Lancement de la configuration...\n";

require 'function-Installer.php';

$comfirme = rdl("Plusieurs fichier system vont être remplacé, continuer ? [oui: o | non: Enter]: ");


$api = '../vendor/nomess/kernel/';
if(!is_null($comfirme)){
	$tabCopyFile = array(
			$api . 'Tools/plugin/cli/prog/noMess/context/Response-dev.php' => $api . 'HttpResponse/HttpResponse.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/WorkException-dev.php' => $api . 'Exception/WorkException.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/Router-dev.php' => $api . 'Router/Router.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/index-dev.php' => '../index.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/WebRouter-dev.php' => $api . 'Web/WebRouter.php'
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