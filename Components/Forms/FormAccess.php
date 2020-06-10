<?php


namespace NoMess\Components\Forms;


use NoMess\Exception\WorkException;

class FormAccess
{

    private const PATH_CACHE            = ROOT . 'Web/public/inc/forms/';
    private const PUBLIC_PATH_CACHE     = 'inc/forms/';

    private ?string $cache;


    /**
     * @param string $name
     * @return string
     * @throws WorkException
     */
    public function get(string $name): string
    {
        $this->getCache($name);

        return $this->cache;
    }


    /**
     *
     * @param string $name
     * @param bool $crash
     * @throws WorkException
     */
    private function getCache(string $name, bool $crash = false): void
    {
        if(file_exists(self::PATH_CACHE . $name. '.twig')){
            $this->cache = self::PUBLIC_PATH_CACHE . $name . '.twig';
        }elseif(file_exists(self::PATH_CACHE . $name . '.php')){
            $this->cache = self::PUBLIC_PATH_CACHE . $name . '.php';
        }elseif($crash === false){
            $this->generate($name);
        }else{
            throw new WorkException('FormAccess encountered an error: Impossible to generate form ' . $name);
        }

    }


    /**
     * Generate the cache
     *
     * @param string $name
     * @throws WorkException
     */
    private function generate(string $name): void
    {

        $className = 'App\\Forms\\' . ucfirst($name);

        $formGenerator = new $className();

        $formGenerator->describe();

        $this->getCache($name, true);
    }

}