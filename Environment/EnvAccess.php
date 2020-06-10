<?php

namespace NoMess\Environment;

class EnvAccess
{
    private const ENV_CAHE          = ROOT . 'App/var/cache/env/env.php';
    private const PATH_ENV_FILE     = ROOT . '.env';


    public function __construct()
    {
        $this->getCache();
    }

    private function getCache(): void
    {
        if(file_exists(self::ENV_CAHE)){
            $tmpEnv = require self::ENV_CAHE;
            $tmpEnv = unserialize($tmpEnv);

            if(filemtime(self::PATH_ENV_FILE) !== $tmpEnv['filemtime']){
                $this->rebuildCache();
            }else{
                foreach ($tmpEnv as $var => $value) {
                    if ($var !== 'filemtime'){
                        putenv("$var=$value");
                    }
                }
            }
        }else{
            $this->rebuildCache();
        }
    }

    private function rebuildCache(): void
    {

        $file = file(self::PATH_ENV_FILE);

        $env = array();

        foreach ($file as $line){
            if(!empty(trim($line))){
                $value = explode('=', $line);

                $env[$value[0]] = $value[1];
                putenv($line);
            }
        }

        $env['filemtime'] = filemtime(self::PATH_ENV_FILE);

        file_put_contents(self::ENV_CAHE, "<?php return '" . serialize($env) . "';");
    }
}