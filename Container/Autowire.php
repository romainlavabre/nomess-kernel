<?php


namespace NoMess\Container;


use NoMess\Exception\WorkException;

class Autowire
{

    private const PATH_CACHE                        = ROOT . 'App/var/cache/di/';

    public array $instance = array();
    public array $definitions;
    public array $cache = array();
    public Container $container;


    /**
     * Resolve class and her dependency
     *
     * @param string $className
     * @param bool $make
     * @return array
     * @throws WorkException
     * @throws \ReflectionException
     */
    public function autowire(string $className, bool $make = false): array
    {
        $key = $this->searchForKey($className);

        $reflectionClass = new \ReflectionClass($className);

        if($reflectionClass->isInstantiable() && !array_key_exists($reflectionClass->getName(), $this->definitions)) {

            $reflectionMethod = $reflectionClass->getConstructor();

            if($reflectionMethod !== null && $this->validAnnotation($reflectionMethod)){

                $tabReflectionParameters = $reflectionMethod->getParameters();

                if ($tabReflectionParameters !== null) {

                    $tabParameters = $this->parametersResolver($tabReflectionParameters, $make, $reflectionClass);

                    $this->instance[$key] = $reflectionClass->newInstanceArgs($tabParameters);
                    $this->setValueProperty($this->instance[$key]);
                    $this->methodResolver($this->instance[$key], $reflectionClass, $make);
                } else {
                    $this->instance[$key] = new $className();
                    $this->setValueProperty($this->instance[$key]);
                    $this->methodResolver($this->instance[$key], $reflectionClass, $make);
                }

            }else{
                $this->instance[$key] = new $className();
                $this->setValueProperty($this->instance[$key]);
                $this->methodResolver($this->instance[$key], $reflectionClass, $make);
            }
        }elseif(array_key_exists($reflectionClass->getName(), $this->definitions)) {
            $this->classResolver($className);
        }else{
            throw new WorkException('Autowire encountered an error: the class ' . $reflectionClass->getName() . ' is not instanciable and any definition is defined');
        }

        return $this->instance;
    }


    /**
     * Resolve method
     *
     * @param object $object
     * @param \ReflectionClass $reflectionClass
     * @param bool $make
     * @throws WorkException
     * @throws \ReflectionException
     */
    private function methodResolver(object $object, \ReflectionClass $reflectionClass, bool $make): void
    {
        $tabMethod = $reflectionClass->getMethods();

        foreach($tabMethod as $reflectionMethod){
            if(strpos($reflectionMethod->getDocComment(), '@Inject') && !$reflectionMethod->isConstructor()){
                $tabParameters = $this->parametersResolver($reflectionMethod->getParameters(), $make, $reflectionClass);

                $reflectionMethod->invokeArgs($object, $tabParameters);
            }
        }
    }


    /**
     * Resolve class definitions
     *
     * @param string $className
     * @return string|null
     * @throws WorkException
     */
    private function classResolver(string $className): ?object
    {
        $returned = $this->definitions[$className];

        if(!empty($returned) && is_array($returned)){

            if(key($returned) === 'get') {
                return $this->container->get(current($returned));
            }else{
                return $this->container->make(current($returned));
            }


        }else{
            throw new WorkException('Autowire encountered an error: you have error in your definition for class ' . $className . '.<br>Must be a array');
        }
    }


    /**
     * Revolve parameters of method
     *
     * @param array $tabReflectionParameters
     * @return \ReflectionParameter[]
     * @throws WorkException
     * @throws \ReflectionException
     */
    private function parametersResolver(array $tabReflectionParameters, bool $make, \ReflectionClass $reflectionClass): array
    {

        $tabParameters = array();

        foreach ($tabReflectionParameters as $reflectionParameter) {
            if ((!array_key_exists($reflectionParameter->getType()->getName(), $this->instance) || $make === true ) && $reflectionParameter->getType() !== null) {

                $this->autowire($reflectionParameter->getType()->getName());

                $keySearch = $this->searchForKey($reflectionParameter->getType()->getName());

                $tabParameters[] = $this->instance[$keySearch];

            } elseif(array_key_exists($reflectionParameter->getType()->getName(), $this->instance) && $make === false){

                $tabParameters[] = $this->instance[$reflectionParameter->getType()->getName()];
                $keySearch = $this->searchForKey($reflectionParameter->getType()->getName());


            } elseif ($reflectionParameter->getType() === null) {
                throw new WorkException('Autowire encountered an error: the constructor of class ' . $reflectionClass->getName() . ' contains a non typed parameter');
            }
        }

        return $tabParameters;
    }


    /**
     * Add value to property
     *
     * @param object $object
     * @throws WorkException
     * @throws \ReflectionException
     */
    private function setValueProperty(object $object): void
    {
        $reflectionClass = new \ReflectionClass($object);

        $tabReflectionProperty = $reflectionClass->getProperties();

        foreach ($tabReflectionProperty as $reflectionProperty){
            $comment = $reflectionProperty->getDocComment();

            if(strpos($comment, '@Inject')){
                if($reflectionProperty->getType() !== null){
                    if(class_exists($reflectionProperty->getType()->getName())){
                        if(!$reflectionProperty->isPublic()){
                            $reflectionProperty->setAccessible(true);
                        }

                        $reflectionProperty->setValue($object, $this->container->get($reflectionProperty->getType()->getName()));

                        $reflectionProperty->setAccessible(false);
                    }
                }
            }
        }
    }


    /**
     * Valid presence of inject annotation
     *
     * @param \ReflectionMethod $reflectionMethod
     * @return bool
     */
    private function validAnnotation(\ReflectionMethod $reflectionMethod): bool
    {
        $comment = $reflectionMethod->getDocComment();

        if(strpos($comment, '@Inject')){
            return true;
        }

        return false;
    }



    /**
     * Return key to search, permitted to manage definitions
     *
     * @param string $className
     * @return string
     */
    private function searchForKey(string $className): string
    {
        if(array_key_exists($className, $this->definitions)){

            return current($this->definitions[$className]);
        }else{
            return $className;
        }
    }


}