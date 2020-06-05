<?php


namespace NoMess\Component\PersistsManager\Builder;


use NoMess\Container\Container;
use NoMess\Exception\WorkException;


class BuilderPersistsManager
{

    private const DIR                   = ROOT . 'App/src/Modules/';
    private const CACHE_PATH            = ROOT . 'App/var/cache/pm/persistsmanager.php';

    private static array $comment = [
        'table' => '@PM\\Table',
        'column' => '@PM\\Column',
        'dependency' => '@PM\\Dependency',
        'keyArray' => '@PM\\KeyArray',
        'patch' => '@PM\\Patch'
    ];

    private string $className;

    private string $keyArray;



    /**
     * Contains table associate to this object
     */
    private string $table;

    /**
     * Contains a property data in this format:
     * array[] = [
     *      column
     *      accessor
     *      mutator
     *      type
     *      scope
     *      table
     *      keyArray
     * ]
     */
    private array $property;


    /**
     * Contains dependency of this class in this format
     * array[dependency class name] = [
     *      scope
     *      method
     * ]
     */
    private array $dependency;



    private Container $container;


    /**
     * @Inject
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Builder
     *
     * @return void
     */
    public function build(string $classname) : void
    {
        $this->className = $classname;
        $reflectionClass = new \ReflectionClass($classname);

        $this->getCommentClass($reflectionClass);
        $this->getKeyArray($reflectionClass->getProperties(), $reflectionClass);
        $this->getCommentProperty($reflectionClass->getProperties(), $reflectionClass);
        $this->getDependency($reflectionClass->getProperties(), $reflectionClass);
        $this->pushCacheData();


    }



    /**
     * Get comment of the class if exists
     *
     * @param \ReflectionClass $reflectionClass
     * @return bool
     * @throws WorkException
     */
    private function getCommentClass(\ReflectionClass $reflectionClass): void
    {
        $commentClass = $reflectionClass->getDocComment();

        if(strpos($commentClass, self::$comment['table']) !== false){
            preg_match('/@PM\\\Table\([a-zA-Z0-9-_&\/\\\~@#]+\)/', $commentClass, $output);

            if(!empty($output[0])){
                $this->table = str_replace(['@PM\\Table(', ')'], '', $output[0]);
            }else{
                throw new WorkException('BuilderPersistsManager encountered an error: table name could not be resolved for ' . $reflectionClass->getName() . ', but exists, please, verify your syntax');
            }
        }else {

            throw new WorkException('BuilderPersistsManager encountered an error: table name could not be resolved for ' . $reflectionClass->getName());
        }
    }


    /**
     * Create properties configuration
     *
     * @param array $properties
     * @throws \ReflectionException
     */
    private function getCommentProperty(array $properties, \ReflectionClass $reflectionClass): void
    {
        foreach ($properties as $value){
            $reflectionProperty = new \ReflectionProperty($reflectionClass->getName(), $value->getName());


            $column = $this->getColumnProperty($reflectionProperty, $reflectionClass);


            if($column !== null){
                $type = $this->getTypeProperty($reflectionProperty);
                $scope = $this->getScopeProperty($reflectionProperty);
                $accessor = $this->getAccessorProperty($reflectionClass, $reflectionProperty, $scope);
                $mutator = $this->getMutatorProperty($reflectionClass, $reflectionProperty, $scope);
                $table = $this->table;
                $keyArray = $this->keyArray;

                $this->property[] = [
                    'column' => $column,
                    'accessor' => $accessor,
                    'mutator' => $mutator,
                    'type' => $type,
                    'scope' => $scope,
                    'table' => $table,
                    'keyArray' => $keyArray
                ];
            }
        }
    }


    /**
     * Take the dependency of this class
     *
     * @param array $properties
     * @param \ReflectionClass $reflectionClass
     * @throws WorkException
     * @throws \ReflectionException
     */
    private function getDependency(array $properties, \ReflectionClass $reflectionClass): void
    {
        foreach ($properties as $value){
            $reflectionProperty = $value;

            if(strpos($reflectionProperty->getDocComment(), self::$comment['dependency'])){
                $this->dependency[$reflectionProperty->getType()->getName()]['scope'] = $this->getScopeProperty($reflectionProperty);

                if($this->dependency[$reflectionProperty->getType()->getName()]['scope'] === 'public'){
                    $this->dependency[$reflectionProperty->getType()->getName()]['mutator'] = $reflectionProperty->getName();
                }else{
                    try {
                        $this->dependency[$reflectionProperty->getType()->getName()]['mutator'] = $reflectionClass->getMethod('set' . ucfirst($reflectionProperty->getName()))->getName();
                    } catch (\ReflectionException $rf) {

                        $accessor = $this->searchPatch($reflectionProperty->getName(), $reflectionClass, 'Mutator');

                        if ($accessor === null) {
                            throw new WorkException('BuilderPersistsManager encountered an error: accessor for property ' .
                                $reflectionProperty->getName() . ' not found, please, respect convention or add patch 
                                "@PM\Patch\Accessor(propertyName). Our searching: get' . ucfirst($reflectionProperty->getName()));
                        }else{
                            $this->dependency[$reflectionProperty->getType()->getName()]['mutator'] = $accessor;
                        }
                    }
                }
            }
        }
    }


    /**
     * Return type of this property
     *
     * @param \ReflectionProperty $reflectionProperty
     * @return string
     */
    private function getTypeProperty(\ReflectionProperty $reflectionProperty): string
    {
        $type = $reflectionProperty->getType()->getName();


        if($type === null){
            return 'mixed';
        }else{
            return $type;
        }
    }


    /**
     * Return column if she's defined
     *
     * @param \ReflectionProperty $reflectionProperty
     * @return string|null
     * @throws WorkException
     */
    private function getColumnProperty(\ReflectionProperty $reflectionProperty, \ReflectionClass $reflectionClass): ?string
    {
        $comment = $reflectionProperty->getDocComment();

        if(strpos($comment, self::$comment['column'])) {
            preg_match('/@PM\\\Column\([a-zA-Z0-9-_&\/\\\~@#]+\)/', $comment, $output);

            if (!empty($output[0])) {
                return str_replace(['@PM\\Column(', ')'], '', $output[0]);
            } else {
                throw new WorkException('BuilderPersistsManager encountered an error: column name could not be resolved for ' . $reflectionClass->getName() . '::'. $reflectionProperty->getName() . ', but exists, please, verify your syntax');
            }
        }

        return null;

    }


    /**
     * Return a method for access the property
     *
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     * @param string $scope
     * @return string
     * @throws WorkException
     */
    private function getAccessorProperty(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty, string $scope): string
    {


        //If scope is public, accessor is the variable name, else, search a accessor method with recovery process if an error occured
        if($scope === 'public') {
            return $reflectionProperty->getName();
        }else {
            try {
                return $reflectionClass->getMethod('get' . ucfirst($reflectionProperty->getName()))->getName();
            } catch (\ReflectionException $rf) {

                $accessor = $this->searchPatch($reflectionProperty->getName(), $reflectionClass, 'Accessor');

                if ($accessor === null) {
                    throw new WorkException('BuilderPersistsManager encountered an error: accessor for property ' . $reflectionProperty->getName() . ' not found, please, respect convention or add patch "@PM\Patch\Accessor(propertyName). Our searching: get' . ucfirst($reflectionProperty->getName()));
                }

                return $accessor;
            }
        }
    }


    /**
     * Return method for mutate the property
     *
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     * @param string $scope
     * @return string
     * @throws WorkException
     */
    private function getMutatorProperty(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty, string $scope): string
    {

        //If scope is public, mutator is the variable name, else, search a mutator method with recovery process if an error occured
        if($scope === 'public') {
            return $reflectionProperty->getName();
        }else{
            try {
                return $reflectionClass->getMethod('set' . ucfirst($reflectionProperty->getName()))->getName();
            } catch (\ReflectionException $rf) {

                $accessor = $this->searchPatch($reflectionProperty->getName(), $reflectionClass, 'Mutator');

                if ($accessor === null) {
                    throw new WorkException('BuilderPersistsManager encountered an error: mutator for property ' .
                        $reflectionProperty->getName() . ' not found, please, respect convention or add patch 
                        "@PM\Patch\Mutator(propertyName). Our searching: set' . ucfirst($reflectionProperty->getName()));
                }

                return $accessor;
            }
        }
    }


    /**
     * Return scope of the property
     *
     * @param \ReflectionProperty $reflectionProperty
     * @return string
     */
    private function getScopeProperty(\ReflectionProperty $reflectionProperty): string
    {
        if($reflectionProperty->isPublic()){
            return 'public';
        }elseif($reflectionProperty->isProtected()){
            return 'protected';
        }else{
            return 'private';
        }
    }


    /**
     * @param \ReflectionProperty[] $properties
     * @param \ReflectionClass $reflectionClass
     * @throws WorkException
     * @throws \ReflectionException
     */
    private function getKeyArray(array $properties, \ReflectionClass $reflectionClass): void
    {

        foreach ($properties as $value){
            $reflectionProperty = new \ReflectionProperty($reflectionClass->getName(), $value->getName());

            $comment = $reflectionProperty->getDocComment();

            if(strpos($comment, self::$comment['keyArray']) !== false){
                $this->keyArray = $reflectionProperty->getName();
            }
        }

        if(!isset($this->keyArray)){
            throw new WorkException('BuilderPersistsManager encountered an error: keyArray property is not found in ' .
                $this->className . ', please, verify your syntaxe or add that');
        }
    }


    /**
     * Search patch for error not found
     *
     * @param string $name
     * @param \ReflectionClass $reflectionClass
     * @param string $type
     * @return string
     * @throws \ReflectionException
     */
    private function searchPatch(string $name, \ReflectionClass $reflectionClass, string $type): string
    {

        foreach ($reflectionClass->getMethods() as $value){
            $reflectionMethod = new \ReflectionMethod($reflectionClass->getName(), $value->getName());

            if(strpos($reflectionMethod->getDocComment(), self::$comment['patch'] . '\\' . $type . '(' . $name . ')')){
                return $reflectionMethod->getName();
            }
        }

        throw new WorkException('BuilderPersistsManager encountered an error: accessor of ' . $name . ' property not found, please, create an patch with this format: @PM\Patch\Accessor(' . $name . ') or respect the convention.<br> Our search: get' . ucfirst($name) . ' or set' . ucfirst($name) . ' and '. self::$comment["patch"] . '\\' . $type . '(' . $name . ')');
    }


    /**
     * Build array formated for work
     *
     * @return array
     */
    private function buildArrayCache(): array
    {

        $array = null;

        if(file_exists(self::CACHE_PATH)){
            $array = require self::CACHE_PATH;
            $array = unserialize($array);
        }else{
            $array = array();
        }

        if(isset($array[$this->className])){
            unset($array[$this->className]);
        }


        $array[$this->className]['keyArray'] = $this->keyArray;
        $array[$this->className]['table'] = $this->table;

        foreach ($this->property as $value){
            $array[$this->className]['property'][] = [
                'column' => $value['column'],
                'accessor' => $value['accessor'],
                'mutator' => $value['mutator'],
                'type' => $value['type'],
                'scope' => $value['scope'],
            ];
        }

        if(isset($this->dependency)) {

            $array[$this->className]['dependency'] =  $this->dependency;

        }

        return $array;
    }


    /**
     * Write in file cache
     */
    private function pushCacheData(): void
    {
        $array = $this->buildArrayCache();

        $content = "<?php
        return '" . serialize($array) . "';";

        file_put_contents(self::CACHE_PATH, $content);
    }

}