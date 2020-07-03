<?php


namespace Nomess\Components\EntityManager\Resolver;


use Nomess\Components\EntityManager\Cache\Cache;
use RedBeanPHP\OODBBean;

class DeleteResolver extends AbstractResolver
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

        $this->mapper[get_class($object)][$object->getId()] = $bean;

        foreach($cache as $property){
            $propertyColumn     = $property[self::COLUMN];
            $propertyType       = $property[self::TYPE];
            $propertyRelation   = $property[self::RELATION];
            $propertyName       = $property[self::NAME];
            $propertyValue      = $this->getPropertyValue($object, $propertyName);

            if($property[self::ACTION] === 'serialize'){
                $bean->$propertyColumn = (!empty($propertyValue)) ? serialize($propertyValue) : NULL;
            }elseif($property[self::ACTION] === NULL){
                $bean->$propertyColumn = (!empty($propertyValue)) ? $propertyValue : NULL;
            }elseif(!empty($propertyRelation)){

                if($propertyRelation['relation'] === 'OneToOne' || $propertyRelation['relation'] === 'OneToMany'){
                    $bean->$propertyColumn = $this->getRelation($object, $propertyRelation, $propertyValue);
                }elseif(!empty($propertyValue)){
                    $tmp = array();

                    foreach($propertyValue as $value){
                        $tmp[] = $this->getRelation($object, $propertyRelation, $value);
                    }

                    $bean->$propertyColumn = $tmp;
                }else{
                    $bean->$propertyColumn = NULL;
                }

            }
        }

        return $bean;
    }


    private function getRelation(object $object, array $relation, $propertyValue): ?OODBBean
    {
        if(!empty($propertyValue)) {
            if(!isset($this->mapper[$relation['type']][$propertyValue->getId()])) {
                return $this->resolve($propertyValue);
            }

            return $this->mapper[$relation['type']][$propertyValue->getId()];
        }

        return NULL;
    }
}
