<?php

echo "Lancement de la configuration...\n";

require 'function-Installer.php';

$comfirme = rdl("Plusieurs fichier vont être remplacé (index.php, WorkException.php, Response.php), continuer ? [oui: o | non: Enter]: ");


$api = '../App/';

$tabConfig['hostVal'] = rdl("Adresse du serveur: ");
$tabConfig['dbnameVal'] = rdl("Nom de la base: ");
$tabConfig['userVal'] = rdl("Utilisateur: ");
$tabConfig['passwordVal'] = rdl("Mot de passe: ");

echo "Édition des fichiers de configuration...\n";

if(@$file = file($api . "config/config-prod.php")){
	for($i = 0; $i < count($file); $i++){
		foreach($tabConfig as $key => $valConf){
			$file[$i] = str_replace($key, $valConf, $file[$i]);
		}
	}


	if(@file_put_contents($api . "config/config-prod.php", $file)){
		echo "Configuration réussie\n";
	}else{
		echo "Echec: Échec de l'enregistrement des fichiers de configuaration\n";
	}
}else{
	echo "Echec: Échec de l'édition des fichiers de configuaration\n";
	die();
}

if(!is_null($comfirme)){
	$tabCopyFile = array(
			'bin/plugin/cli/prog/noMess/context/Request-prod.php' => $api . 'vendor/NoMess/Request.php',
			'bin/plugin/cli/prog/noMess/context/Response-prod.php' => $api . 'vendor/NoMess/Response.php',
			'bin/plugin/cli/prog/noMess/context/WorkException-prod.php' => $api . 'vendor/NoMess/WorkException.php',
			'bin/plugin/cli/prog/noMess/context/index-prod.php' => '../index.php',
			'bin/plugin/cli/prog/noMess/context/WebRooter-prod.php' => '../Web/WebRooter.php'
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
}