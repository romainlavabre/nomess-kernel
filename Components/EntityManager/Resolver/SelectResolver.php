<?php


namespace Nomess\Components\EntityManager\Resolver;


use Nomess\Annotations\Inject;
use Nomess\Components\EntityManager\Cache\Cache;
use Nomess\Components\EntityManager\Container\Container;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;


class SelectResolver
{

    private const ACTION      = 'action';
    private const COLUMN      = 'column';
    private const RELATION    = 'relation';
    private const TYPE        = 'type';
    private const NAME        = 'name';
    private const CLASSNAME     = 'classname';

    /**
     * @Inject()
     */
    private Cache $cache;

    /**
     * @Inject()
     */
    private Container $container;

    private array $mapper = array();

    public function resolve(string $classname, $idOrSql, ?array $parameters)
    {
        $cache = $this->cache->getCache($this->getShortName($classname), $classname, '__SELECT__');

        $data = $this->getData($this->request($this->getTable($cache), $idOrSql, $parameters), $cache);

        if(!empty($data)) {
            if(preg_match('/^[0-9]+$/', $idOrSql)) {
                return $data[0];
            }

            return $data;
        }
    }

    protected function getData($beans, array $cache)
    {
        unset($cache['nomess_table']);

        if(empty($beans) || empty($cache) || $beans[0]->isEmpty()){
            return NULL;
        }

        $list = array();

        foreach($beans as $bean){

            $target = NULL;

            foreach($cache as $columnName => $propertyData){
                $classname          = $propertyData[self::CLASSNAME];
                $propertyName       = $propertyData[self::NAME];

                if($target === NULL) {
                    if($this->container->get($classname, $bean->id)) {
                        $list[] = $this->container->get($classname, $bean->id);
                        break 1;
                    }

                    $target = new $classname();
                    $this->subscribeToMapper($target, $bean);

                    $reflectionProperty = new \ReflectionProperty($classname, $propertyName);
                    $reflectionProperty->setAccessible(TRUE);
                    $reflectionProperty->setValue($target, $bean->id);
                    $this->container->set($classname, $target);
                }

                if($propertyName !== 'id') {

                    $propertyColumn = $propertyData[self::COLUMN];
                    $purgeLazyLoad = $bean->$propertyColumn;

                    $propertyAction = $propertyData[self::ACTION];
                    $propertyRelation = $propertyData[self::RELATION];
                    $propertyValue = (!empty($bean->$propertyColumn)) ? $bean->$propertyColumn : NULL;

                    $reflectionProperty = new \ReflectionProperty($classname, $propertyName);

                    if(!$reflectionProperty->isPublic()) {
                        $reflectionProperty->setAccessible(TRUE);
                    }

                    if($propertyAction === 'unserialize') {
                        $reflectionProperty->setValue($target, (is_array($propertyValue)) ? unserialize($propertyValue) : NULL);
                    } elseif($propertyAction === NULL) {
                        $reflectionProperty->setValue($target, $propertyValue);
                    } elseif(!empty($propertyRelation)) {
                        if($propertyValue !== NULL) {
                            if($propertyRelation['relation'] === 'ManyToOne' || $propertyRelation['relation'] === 'ManyToMany') {
                                $tmp = array();

                                foreach($propertyValue as $value){
                                    $tmp[] = $this->getRelation($propertyRelation, $value);
                                }

                                $reflectionProperty->setValue($target, $tmp);
                            } elseif($propertyRelation['relation'] === 'OneToOne' || $propertyRelation['relation'] === 'OneToMany') {
                                $reflectionProperty->setValue($target, $this->getRelation($propertyRelation, $propertyValue));
                            }
                        }
                    }
                }
            }

            $list[] = $target;
        }

        return $list;

    }


    private function getRelation(array $relation, $propertyValue): ?object
    {
        if($propertyValue !== NULL) {

            $classname = $relation['type'];

            if(!empty($this->mapper) && isset($this->mapper[$classname])) {
                foreach($this->mapper[$classname] as $value) {
                    if($value['bean']->id === $propertyValue->id) {
                        return $value['object'];
                    }
                }
            }

            return$this->getData([$propertyValue], $this->cache->getCache($this->getShortName($classname), $classname, '__SELECT__'))[0];
        }

        return NULL;
    }

    private function request(string $tableName, $idOrSql, ?array $parameters)
    {
        if(preg_match('/^[0-9]+$/', $idOrSql)){

            return [R::load($tableName, $idOrSql)];
        }elseif(is_string($idOrSql)){
            $data = R::find($tableName, $idOrSql, (!empty($parameters)) ? $parameters : []);

            return (is_array($data)) ? $data : [$data];
        }else{
            return R::findAll($tableName);
        }
    }

    private function subscribeToMapper(object $target, object $bean): void
    {
        $this->mapper[get_class($target)][] = [
            'object' => $target,
            'bean' => $bean
        ];
    }

    private function getShortName(string $classname): string
    {
        return substr(strrchr($classname, '\\'), 1);
    }

    private function getTable(array &$data): string
    {
        $table = $data['nomess_table'];
        unset($data['nomess_table']);
        return $table;
    }


}
