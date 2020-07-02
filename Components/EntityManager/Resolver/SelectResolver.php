<?php


namespace Nomess\Components\EntityManager\Resolver;


use NoMess\Components\EntityManager\Container\Container;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class SelectResolver extends AbstractResolver
{
    private Container $entityContainer;

    public function __construct(Container $entityContainer)
    {
        $this->entityContainer = $entityContainer;
    }

    private bool $returnObject = FALSE;

    public function resolve(string $classname, $idOrSql, ?array $parameters)
    {
        $cache = $this->getCache($this->getShortenName($classname), $classname);
        $data = $this->requestResolver($this->getTable($cache), $idOrSql, $parameters);

        return $this->setObject($classname, $data, $cache);

    }

    private function setObject(string $classname, ?array $data, array $cache)
    {
        if(!empty($data) && !$data[0]->isEmpty()){

            $list = array();

            /** @var OODBBean $bean */
            foreach($data as $bean){

                $target = new $classname();

                foreach($cache as $propertyName => $mapping){

                    $reflectionProperty = new \ReflectionProperty($target, $propertyName);
                    $columnName = $mapping['column'];

                    if($mapping['action'] === 'unserialize'){
                        if($bean->$columnName !== NULL) {
                            $value = unserialize($bean->$columnName);

                            $this->setProperty($reflectionProperty, $target, $value);
                        }
                    }elseif($mapping['action'] === 'iteration'){
                        $value = $bean->$columnName;

                        if(!empty($value)){
                            $tmpList = array();

                            foreach($value as $object){
                                $resolved = $this->resolverLauncher($mapping['type'], $object);

                                // Call container to avoid the duplication of object
                                $containerProvide = $this->entityContainer->get($mapping['type'], $bean->id);
                                if($containerProvide !== NULL) {
                                    $tmpList[] = $containerProvide;
                                }else{
                                    $this->entityContainer->set($mapping['type'], $resolved);
                                    $tmpList[] = $resolved;
                                }
                            }

                            $this->setProperty($reflectionProperty, $target, $tmpList);
                        }
                    }elseif($mapping['action'] === 'bean'){

                        if(!empty($bean->$columnName)){

                            $resolved = $this->resolverLauncher($mapping['type'], $bean->$columnName);

                            // Call container to avoid the duplication of object
                            $containerProvide = $this->entityContainer->get($mapping['type'], $bean->id);

                            if($containerProvide !== NULL) {
                                $this->setProperty(
                                    $reflectionProperty,
                                    $target,
                                    $containerProvide);
                            }else{
                                $this->entityContainer->set($mapping['type'], $resolved);
                                $this->setProperty(
                                    $reflectionProperty,
                                    $target,
                                    $resolved);
                            }
                        }
                    }else{
                        $this->setProperty($reflectionProperty, $target, $bean->$columnName);
                    }
                }

                $list[$target->getId()] = $target;
            }

            if(!$this->returnObject) {
                return $list;
            }else{
                return current($list);
            }
        }

        return NULL;
    }

    private function requestResolver(string $tableName, $idOrSql, ?array $parameters)
    {
        if(preg_match('/^[0-9]+$/', $idOrSql)){
            $this->returnObject = TRUE;

            return [R::load($tableName, $idOrSql)];
        }elseif(is_string($idOrSql)){
            $data = R::find($tableName, $idOrSql, (!empty($parameters)) ? $parameters : []);

            return (is_array($data)) ? $data : [$data];
        }else{
            return R::findAll($tableName);
        }
    }

    private function setProperty(\ReflectionProperty $reflectionProperty, object $target, $data): void
    {
        $data = (empty($data)) ? NULL : $data;

        if($reflectionProperty->isPublic()) {
            $reflectionProperty->setValue($target, $data);
        } else {
            $reflectionProperty->setAccessible(TRUE);
            $reflectionProperty->setValue($target, $data);
        }

    }

    private function resolverLauncher(string $type, object $object): object
    {
        $objectCache = $this->getCache(
            $this->getShortenName($type),
            $type
        );

        unset($objectCache['nomess_table']);

        return $this->setObject(
            $type,
            [$object],
            $objectCache
        );
    }

}
