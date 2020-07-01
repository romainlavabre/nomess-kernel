<?php

namespace Nomess\Components\EntityManager\Resolver;



class UpdateResolver extends AbstractAlerationResolver
{

    protected function resolverLauncher(object $data, array $configuration): array
    {
        $classname = get_class($data);

        if(!array_key_exists($classname, $configuration) || $configuration[$classname] === FALSE) {

            $pass = [
                'classname' => $this->getShortenName($classname),
                'fullClassname' => $classname,
                'data' => $data,
                'configuration' => $configuration
            ];

            return $this->resolve($pass);
        }

        return array();
    }
}
