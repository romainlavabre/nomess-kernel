<?php


namespace NoMess\Container;



class Container
{

    private const PATH_DEFINITION                   = ROOT . 'App/config/definitions.php';
    private const PATH_CACHE                        = ROOT . 'App/var/cache/di/';


    private Autowire $autowire;
    private array $instance = array();
    private array $definition;


    public function __construct()
    {
        $this->definition = require self::PATH_DEFINITION;
        $this->instance[Container::class] = $this;
    }


    /**
     * Return instance with singleton pattern
     *
     * @param string $className
     * @return object
     * @throws \NoMess\Exception\WorkException
     * @throws \ReflectionException
     */
    public function get(string $className): object
    {

        $key = $this->searchForKey($className);

        if(array_key_exists($key, $this->instance)){
            return $this->instance[$key];
        }else{

            if(!isset($this->autowire)){
                $this->autowire = new Autowire();
                $this->autowire->instance = $this->instance;
                $this->autowire->definitions = $this->definition;
                $this->autowire->container = $this;
            }

            $this->instance = $this->autowire->autowire($className);

            return $this->instance[$key];
        }
    }




    /**
     * Return new Instance
     *
     * @param string $className
     * @return object
     * @throws \NoMess\Exception\WorkException
     * @throws \ReflectionException
     */
    public function make(string $className): object
    {

        $key = $this->searchForKey($className);

        if(!isset($this->autowire)){
            $this->autowire = new Autowire();
            $this->autowire->instance = $this->instance;
            $this->autowire->definitions = $this->definition;
            $this->autowire->container = $this;
        }

        $this->instance = $this->autowire->autowire($className, true);

        return $this->instance[$key];
    }


    /**
     * Return key to search, permitted to manage definitions
     *
     * @param string $className
     * @return string
     */
    private function searchForKey(string $className): string
    {
        if(array_key_exists($className, $this->definition)){
            return current($this->definition[$className]);
        }else{
            return $className;
        }
    }

}