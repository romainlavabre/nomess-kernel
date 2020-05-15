#!/usr/local/bin/php
<?php

use DI\ContainerBuilder;


ignore_user_abort(true);
set_time_limit(0);


function process(string $configPath) : void
{

    //Managment
    $nbreCycle = 0;
    $stop = false;


    //take setting
    $configTmp = require $configPath;
    $config = array();


    //Dispatch data
    foreach($configTmp as $key => $value){
        if($key === 'tts' || $key === 'nc' || $key === 'autoload' || $key === 'success_log' || $key === 'error_log'){
            $config[$key] = $value;
            unset($configTmp[$key]);
        }
    }

    $callable = $configTmp;
    unset($configTmp);


    //Service
    require $config['autoload'];

    $builder = new ContainerBuilder();
    $builder->useAnnotations(true);
    $builder->addDefinitions(str_replace('component/worker.php', 'di-definitions.php', $configPath));
    $container = $builder->build();

    while($stop === false){


        foreach($callable as $key => $value){
            try{

                $value();

                file_put_contents($config['success_log'], 
                    date('d/m/Y H:i:s') . " => Function with id " . $key . " have success\n\n", 
                    FILE_APPEND
                );
            }catch(Throwable $e){
                file_put_contents($config['error_log'], 
                    date('d/m/Y H:i:s') . " => Function with id " . $key . " have crash with message: " . $e->getMessage() . "\n\n",
                    FILE_APPEND
                );
            }
        }

        $tabTask = scandir(__DIR__ . '/Storage');


        foreach($tabTask as $key => $value){
            if($value !== '.' && $value !== '..'){
                $tabUnitTask = null;


                try{
                    $tabUnitTask = require __DIR__ . '/Storage/' . $value;

                    if($tabUnitTask['permanent'] === false || !is_array($tabUnitTask)){
                        unlink(__DIR__ . "/Storage/$value");
                    }

                    $tabUnitTask['function'];

                    file_put_contents($config['success_log'], 
                        date('d/m/Y H:i:s') . " => Function with id " . $tabUnitTask['taskId'] . " have success\n\n", 
                        FILE_APPEND
                    );
                }catch(Throwable $e){
                    file_put_contents($config['error_log'], 
                        date('d/m/Y H:i:s') . " => Function with id " . $tabUnitTask['taskId'] . " have crash with message: " . $e->getMessage() . "\n\n",
                        FILE_APPEND
                    );

                    if($tabUnitTask === null){
                        unlink(__DIR__ . "/Storage/$value");
                    }
                }
            }
        }

        unset($tabTask);

        sleep($config['tts']);
        $nbreCycle++;

        if($config['nc'] === $nbreCycle){
            $stop = true;
        }
    }

}


if(!isset($argv[1])){
    echo "Le worker a besoin du chemin vers le fichier de configuration\n";
    die;
}else{
    process($argv[1]);
}