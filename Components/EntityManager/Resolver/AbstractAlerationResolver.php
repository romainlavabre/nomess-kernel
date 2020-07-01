<?php


namespace Nomess\Components\EntityManager\Resolver;


use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

abstract class AbstractAlerationResolver extends AbstractResolver
{
    public function resolve(array $data): array
    {
        $cache = $this->getCache($data['classname'], $data['fullClassname']);
        $list = array();

        if(!empty($cache)){
            $bean = R::dispense($this->getTable($cache));

            foreach($cache as $propertyName => $value){

                $reflectionProperty = new \ReflectionProperty($data['data'], $propertyName);
                $columnName = $value['column'];
                $propertyValue = $this->getValueProperty($reflectionProperty, $data['data']);

                if($value['action'] === NULL){

                    $this->setColumn($bean, $columnName, $propertyValue);
                }elseif($value['action'] === 'serialize'){
                    $this->setColumn($bean, $columnName, $propertyValue);
                }elseif($value['action'] === 'iteration'){
                    if(!empty($propertyValue)){
                        $tmpList = array();

                        foreach($propertyValue as $object){

                            $resolved = $this->resolverLauncher($object, $data['configuration']);

                            if(!empty($resolved)) {
                                $list = array_merge($list, $resolved);
                                $tmpList[] = $resolved[0];
                            }
                        }

                        $this->setColumn($bean, $columnName, $tmpList);
                    }
                }elseif($value['action'] === 'bean' && !empty($propertyValue)){

                    $resolved = $this->resolverLauncher($propertyValue, $data['configuration']);

                    $list = array_merge($list, $resolved);
                    $this->setColumn($bean, $columnName, $resolved[0]);

                }
            }

            $list[] = $bean;
        }

        return $list;
    }

    protected function setColumn(OODBBean $bean, string $columnName, $data): void
    {
        if($columnName !== 'id') {
            $bean->$columnName = (!empty($data)) ? $data : NULL;
        }else{
            if($data === NULL){
                $bean->$columnName = 0;
            }else{
                $bean->$columnName = $data;
            }
        }
    }

    protected function getValueProperty(\ReflectionProperty $reflectionProperty, object $object)
    {
        if($reflectionProperty->isPublic()) {

            try {
                return $reflectionProperty->getValue($object);
            }catch(\Throwable $e){}
        }else{
            $reflectionProperty->setAccessible(TRUE);

            try {
                return $reflectionProperty->getValue($object);
            }catch(\Throwable $e){}
        }

        return NULL;
    }
}
