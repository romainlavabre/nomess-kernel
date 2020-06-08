#!/usr/local/bin/php
<?php




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

    $container = new NoMess\Container\Container();

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
                    date('d/m/Y H:i:s') . " => Function with id " . $key . " has crash with the message: " . $e->getMessage() . "\n\n",
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
                        date('d/m/Y H:i:s') . " => Function with id " . $tabUnitTask['taskId'] . " has success\n\n",
                        FILE_APPEND
                    );
                }catch(Throwable $e){
                    file_put_contents($config['error_log'], 
                        date('d/m/Y H:i:s') . " => Function with id " . $tabUnitTask['taskId'] . " has crash with the message: " . $e->getMessage() . "\n\n",
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
    echo "Worker encountered an error: need the path to configuration\n";
    die;
}else{
    process($argv[1]);
}