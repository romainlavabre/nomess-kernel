<?php
echo "Launch of configuration...\n";

require __DIR__ . '/function-Installer.php';


$api = 'vendor/nomess/kernel/';
	$tabCopyFile = array(
			$api . 'Tools/Console/noMess/context/index-dev.php' => 'public/index.php'
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