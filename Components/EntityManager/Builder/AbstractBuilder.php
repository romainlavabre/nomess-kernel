<?php


namespace Nomess\Components\EntityManager\Builder;


use Nomess\Exception\NotFoundException;
use Nomess\Exception\ORMException;

abstract class AbstractBuilder
{

    protected function columnResolver(string $propertyName, string $classname, ?array $relation = NULL): string
    {

        // If short name is empty, the value is type of php class
        if(!empty($relation)){
            if($relation['relation'] === 'many'){
                return 'own' . ucfirst($this->tableResolver($this->getShortenName($relation['type']))) . 'List';
            }elseif($relation['relation'] === 'ManyToMany'){
                return 'shared' . ucfirst($this->tableResolver($this->getShortenName($relation['type']))) . 'List';
            }elseif($relation['relation'] === 'one'){
                return $this->tableResolver($this->getShortenName($relation['type']));
            }
        }

        if(preg_match('/[A-Za-z0-9_]+/', $propertyName)){
            if(!preg_match('/[A-Za-z0-9_]+_id/', $propertyName)){
                return $propertyName;
            }
        }

        throw new ORMException("ORM encountered an error: your property $$propertyName in class " . $classname .
            ' isn\'t compatible with redbean, Accepted:<br><br> A-Za-z0-9_<br>Property finishing by "_id" in exclude');
    }


    protected function tableResolver(string $classname): string
    {
        $classname = preg_replace('/0-9_/', '', $classname);

        return mb_strtolower($classname);
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     * @return string|null
     */
    protected function searchType(\ReflectionProperty $reflectionProperty): ?string
    {
        preg_match('/@var ([A-Za-z0-1_\\\]+)\[?\]?[|null]*/', $reflectionProperty->getDocComment(), $output);

        if(!empty($output)){
            return $output[1];
        }

        return NULL;
    }


    protected function getShortenName(string $classname): string
    {
        return substr(strrchr($classname, '\\'), 1);
    }

    protected function criticalClassResolver(string $classname, \ReflectionClass $reflectionClass): ?string
    {
        //Search for class in used namespace
        $file = file($reflectionClass->getFileName());
        $found = array();


        foreach($file as $line) {

            if(strpos($line, $classname) !== FALSE && strpos($line, 'use') !== FALSE){

                preg_match('/ +[A-Za-z0-9_\\\]*/', $line, $output);
                $found[] = trim($output[0]);
            }
        }
        if(empty($found)){
            if(class_exists($reflectionClass->getNamespaceName() . '\\' . $classname)){
                return $reflectionClass->getNamespaceName() . '\\' . $classname;
            }
        }elseif(count($found) === 1){
            return $found[0];
        }
        throw new ORMException('ORM encountered an error: impossible to resolving the type ' . $classname . ' in @var annotation in ' . $reflectionClass->getName());

    }

    /**
     * Search the type of property (real type)
     *
     * @param \ReflectionProperty $reflectionProperty
     * @return string
     * @throws ORMException
     */
    protected function getType(\ReflectionProperty $reflectionProperty): string
    {
        if($reflectionProperty->getType() !== NULL){
            $type = $reflectionProperty->getType()->getName();

            // If type is array, can be an array of relations or an arbitrary array
            if($type === 'array'){
                $arrayContentType = $this->searchType($reflectionProperty);

                if($arrayContentType !== NULL){
                    return $this->criticalClassResolver($arrayContentType, $reflectionProperty->getDeclaringClass());
                }else{
                    return 'array';
                }
            }

            return $type;
        }

        throw new ORMException('ORM encountered an error: property ' . $reflectionProperty->getName() .
            ' in class ' . $reflectionProperty->getDeclaringClass()->getName() . ' has not type');
    }

    /**
     * Search the relation
     *
     * @param \ReflectionProperty $reflectionProperty
     * @return array|null
     * @throws ORMException
     */
    protected function relationResolver(\ReflectionProperty $reflectionProperty): ?array
    {

        $type = $this->getType($reflectionProperty);

        if(class_exists($type)){

            if($reflectionProperty->getType()->getName() === 'array'){
                if(strpos($reflectionProperty->getDocComment(), '@ManyToMany') !== FALSE){
                    return [
                        'relation' => 'ManyToMany',
                        'type' => $type
                    ];
                }

                return [
                    'relation' => 'many',
                    'type' => $type
                ];
            }

            return [
                'relation' => 'one',
                'type' => $type
            ];
        }

        return NULL;
    }
}
