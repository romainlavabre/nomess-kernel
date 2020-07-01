<?php


namespace Nomess\Components\EntityManager\Builder;


use Nomess\Exception\ORMException;

class UpdateBuilder extends AbstractBuilder
{

    public function builder(string $classname): array
    {
        $reflectionClass = new \ReflectionClass($classname);
        return array_merge(['nomess_table' => $this->tableResolver($this->getShortenName($classname))], $this->propertyResolver($reflectionClass->getProperties()));
    }

    /**
     * Build property for cache
     *
     * @param \ReflectionProperty[]|null $reflectionProperties
     * @return array
     * @throws ORMException
     */
    private function propertyResolver(?array $reflectionProperties): array
    {
        $list = array();

        if(!empty($reflectionProperties)){

            $declaringClass = $reflectionProperties[0]->getDeclaringClass()->getName();

            foreach($reflectionProperties as $reflectionProperty){

                $propertyType = $this->getType($reflectionProperty);
                $propertyName = $reflectionProperty->getName();

                $list[$propertyName] = [
                    'action' => $this->getAction($reflectionProperty),
                    'column' => $this->columnResolver(
                                    $propertyName,
                                    $declaringClass,
                                    $this->relationResolver($reflectionProperty)
                                ),
                    'type' => $propertyType
                ];
            }
        }

        return $list;
    }

    /**
     * Precise the action to executed
     *
     * @param \ReflectionProperty $reflectionProperty
     * @return string|null
     * @throws ORMException
     */
    private function getAction(\ReflectionProperty $reflectionProperty): ?string
    {
        $type = $this->getType($reflectionProperty);

        if($type === 'array'){
            return 'serialize';
        }elseif(class_exists($type)
            && $reflectionProperty->getType()->getName() === 'array'){

            return 'iteration';

        }elseif(class_exists($type)){
            return 'bean';
        }

        return NULL;

    }
}
