<?php


namespace Nomess\Components\EntityManager\Resolver;

use RedBeanPHP\R;
use RedBeanPHP\OODBBean;

abstract class AbstractResolver
{
    protected const ACTION      = 'action';
    protected const COLUMN      = 'column';
    protected const RELATION    = 'relation';
    protected const TYPE        = 'type';
    protected const NAME        = 'name';


    protected function getShortName(string $classname): string
    {
        return substr(strrchr($classname, '\\'), 1);
    }

    protected function getBean(array &$data): OODBBean
    {
        $table = $data['nomess_table'];
        unset($data['nomess_table']);
        return R::dispense($table);
    }

    protected function getPropertyValue(object $object, string $propertyName)
    {
        $reflectionProperty = new \ReflectionProperty(get_class($object), $propertyName);

        if(!$reflectionProperty->isPublic()){
            $reflectionProperty->setAccessible(TRUE);
        }

        $value = NULL;

        try{
            $value = $reflectionProperty->getValue($object);
        }catch(\Throwable $e){}

        return $value;
    }


    abstract public function resolve(object $object): ?OODBBean;
}
