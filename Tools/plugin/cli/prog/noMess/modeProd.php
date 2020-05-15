<?php

use NoMess\DiBuilder\DiBuilder;

echo "Lancement de la configuration...\n";

require 'function-Installer.php';

$comfirme = rdl("Plusieurs fichier system vont être remplacé, continuer ? [oui: o | non: Enter]: ");


$api = '../vendor/nomess/kernel/';


if(!is_null($comfirme)){
	$tabCopyFile = array(
			$api . 'Tools/plugin/cli/prog/noMess/context/Distributor-prod.php' => $api . 'Manager/Distributor.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/WorkException-prod.php' => $api . 'Exception/WorkException.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/Router-prod.php' => $api . 'Router/Router.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/index-prod.php' => '../index.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/WebRouter-prod.php' => $api . 'Web/WebRouter.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/DataManager-prod.php' => $api . 'DataManager/DataManager.php'
	);

	foreach($tabCopyFile as $key => $value){
		$tabFile = explode("/", $key);
		$tabLength = count($tabFile);

		if(copy($key, $value)){
			echo "Fichier " . $tabFile[$tabLength - 1] . " réinitialisé\n";
		}else{
			echo "Error: Le fichier " . $tabFile[$tabLength - 1] . " n'a pas pu être créé\n";
		}
	}

	require_once '../vendor/nomess/kernel/DiBuilder/DiBuilder.php';
	$build = new DiBuilder();
	$build->diBuilder();
}