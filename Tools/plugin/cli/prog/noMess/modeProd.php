<?php

use NoMess\DiBuilder\DiBuilder;

echo "Lancement de la configuration...\n";

require __DIR__ . '/function-Installer.php';


$api = 'vendor/nomess/kernel/';
$tabCopyFile = array(
        $api . 'Tools/plugin/cli/prog/noMess/context/index-prod.php' => 'Web/index.php'
);

foreach($tabCopyFile as $key => $value){
    $tabFile = explode("/", $key);
    $tabLength = count($tabFile);

    if(copy($key, $value)){
        echo "File " . $tabFile[$tabLength - 1] . " reset\n";
    }else{
        echo "Error: The file " . $tabFile[$tabLength - 1] . " cannot be created\n";
    }
}
