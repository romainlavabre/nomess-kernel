<?php
require 'function-Installer.php';

$api = '../App/config/';

echo "Lancement de la configuration...\n";
echo "Récupération des fichiers de configuration...\n";
echo "Controle de la demande...\n";

if(@$file = file($api . 'config.nws')){
	foreach($file as $value){
		if(strpos($value, "config-dev")){
			if(strpos($value, "TRUE")){
				echo "Demande de configuration refusé\n";
				$error[] = "Vous avez déjà configuré le mode prod\n";
			}
		}
	}
}

echo "Demande de configuration autorisé\n";

$tabConfig['hostVal'] = rdl("Adresse du serveur: ");
$tabConfig['dbnameVal'] = rdl("Nom de la base: ");
$tabConfig['userVal'] = rdl("Utilisateur: ");
$tabConfig['passwordVal'] = rdl("Mot de passe: ");

echo "Édition des fichiers de configuration...\n";

if(@$file = file($api . 'config-dev.php')){

	for($i = 0; $i < count($file); $i++){
		foreach($tabConfig as $key => $valConf){
			$file[$i] = str_replace($key, $valConf, $file[$i]);
		}
	}


	if(@file_put_contents($api . 'config-dev.php', $file)){
		echo "Configuration réussie\n";
	}else{
		echo "Echec: Échec de l'enregistrement des fichiers de configuaration\n";
		$error[] = "Echec de l'enregistrement de config-dev.php\n";
	}
}else{
	echo "Echec: Échec de l'édition des fichiers de configuaration\n";
	$error[] = "Echec de l'édition de config-dev.php\n";
}