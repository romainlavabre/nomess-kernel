<?php


namespace Nomess\Components\EntityManager\Resolver;


use Nomess\Annotations\Inject;
use Nomess\Components\EntityManager\Cache\Cache;
use Nomess\Components\EntityManager\EntityManagerInterface;
use RedBeanPHP\OODBBean;

class CreateResolver extends AbstractResolver
{

    /**
     * @Inject()
     */
    private Cache $cache;

    private array $mapper = array();

    public function resolve(object $object): ?OODBBean
    {
        $cache = $this->cache->getCache($this->getShortName(get_class($object)), get_class($object), '__UPDATED__');
        return $this->getData($object, $cache);
    }

    protected function getData(object $object, array $cache): OODBBean
    {
        $bean = $this->getBean($cache);

        foreach($cache as $property){
            $propertyColumn     = $property[self::COLUMN];
            $propertyType       = $property[self::TYPE];
            $propertyRelation   = $property[self::RELATION];
            $propertyName       = $property[self::NAME];
            $propertyValue      = $this->getPropertyValue($object, $propertyName);


            if($property[self::ACTION] === 'serialize'){
                $bean->$propertyColumn = (!empty($propertyValue)) ? serialize($propertyValue) : NULL;
            }elseif($property[self::ACTION] === NULL){
                if($propertyName !== 'id') {
                    $bean->$propertyColumn = (!empty($propertyValue)) ? $propertyValue : NULL;
                }
            }elseif(!empty($propertyRelation)){

                $columnValue = NULL;

                if($propertyRelation['relation'] === 'OneToOne' || $propertyRelation['relation'] === 'OneToMany'){
                    $columnValue = $this->getRelation($object, $propertyRelation, $propertyValue);
                }elseif(!empty($propertyValue)){
                    $tmp = array();

                    foreach($propertyValue as $value){
                        $tmp[] = $this->getRelation($object, $propertyRelation, $value);
                    }

                    $columnValue = $tmp;
                }

                if(!empty($columnValue)){
                    $bean->$propertyColumn = $columnValue;
                }
            }
        }

        return $bean;
    }


    private function getRelation(object $object, array $relation, $propertyValue): ?OODBBean
    {
        if(!empty($propertyValue)) {

            if(!empty($this->mapper)){
                foreach($this->mapper as $tmp){
                    foreach($tmp as $value){
                        if($value['object'] === $propertyValue){
                            return $value['bean'];
                        }
                    }
                }
            }

            $bean = $this->resolve($propertyValue);

            $this->mapper[$relation['type']][] = [
                'object' => $propertyValue,
                'bean' => $bean
            ];

            return $bean;
        }

        return NULL;
    }
}
