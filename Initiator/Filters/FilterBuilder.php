<?php


namespace Nomess\Initiator\Filters;


use Nomess\Exception\MissingConfigurationException;

class FilterBuilder
{
    private const FILTERS           = ROOT . 'src/Filters/';

    public function build(): array
    {
        $filters = scandir(self::FILTERS);
        $found = array();

        foreach($filters as $filter){
            if($filter !== '.' && $filter !== '..'){
                $filterName = 'App\\Filters\\' . str_replace('.php', '', $filter);
                $regex = $this->getAnnotation($filterName);

                $found[$filterName] = $regex;
            }
        }

        return $found;
    }

    /**
     * @param string $classname
     * @return string
     * @throws MissingConfigurationException
     * @throws \ReflectionException
     */
    private function getAnnotation(string $classname): string
    {
        $reflectionClass = new \ReflectionClass($classname);

        $comments = $reflectionClass->getDocComment();

        if(preg_match('/@Filter\("(.+)"\)/', $comments, $output)){
            return $output[1];
        }

        throw new MissingConfigurationException('You filter annotation is incomplete in ' . $classname);
    }
}
