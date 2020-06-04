<?php
echo "Launch of configuration...\n";

require __DIR__ . '/function-Installer.php';

$comfirme = rdl("Many file system will be remove, pursue ? [oui: o | non: Enter]: ");


$api = 'vendor/nomess/kernel/';
if(!is_null($comfirme)){
	$tabCopyFile = array(
			$api . 'Tools/plugin/cli/prog/noMess/context/Distributor-dev.php' => $api . 'Manager/Distributor.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/WorkException-dev.php' => $api . 'Exception/WorkException.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/Router-dev.php' => $api . 'Router/Router.php',
			$api . 'Tools/plugin/cli/prog/noMess/context/index-dev.php' => 'Web/index.php',
	);

	foreach($tabCopyFile as $key => $value){
		$tabFile = explode("/", $key);
		$tabLength = count($tabFile);

		if(@copy($key, $value)){
			echo "File " . $tabFile[$tabLength - 1] . " reset\n";
		}else{
			echo "Error: File " . $tabFile[$tabLength - 1] . " cannot be created\n";
		}
	}
}