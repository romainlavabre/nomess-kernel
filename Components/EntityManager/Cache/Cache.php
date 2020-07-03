<?php


namespace Nomess\Components\EntityManager\Cache;


use Nomess\Annotations\Inject;
use Nomess\Components\EntityManager\Builder\SelectBuilder;
use Nomess\Components\EntityManager\Builder\UpdateBuilder;
use Nomess\Container\Container;

class Cache
{
    private const CACHE             = ROOT . 'var/cache/em/';

    private array $calledCache = array();

    /**
     * @Inject()
     */
    protected Container $container;

    public function getCache(string $classname, string $fullClassname, string $context): ?array
    {
        if(!array_key_exists($classname, $this->calledCache)) {

            $filename = self::CACHE . $context . str_replace('\\', '_', $fullClassname) . '.php';

            if(file_exists($filename)) {
                $cache = require $filename;
                $cache = unserialize($cache);

                if($this->validConsistencyCache($fullClassname, $cache)) {
                    $this->calledCache[$classname] = $cache;
                    return $cache;
                }
            }

            return $this->setCache($this->getRefreshCache($context, $fullClassname), new \ReflectionClass($fullClassname), $context);
        }else{
            return $this->calledCache[$classname];
        }
    }

    private function setCache(array $cache, \ReflectionClass $reflectionClass, string $context): array
    {
        $cache['nomess_filectime'] = filectime($reflectionClass->getFileName());

        file_put_contents(self::CACHE . $context . str_replace('\\', '_', $reflectionClass->getName()) . '.php', '<?php return \'' . serialize($cache) . '\';');

        unset($cache['nomess_filectime']);
        return $cache;
    }

    private function validConsistencyCache(string $classname, array &$cache): bool
    {
        $reflectionClass = new \ReflectionClass($classname);
        $filename = $reflectionClass->getFileName();

        $time = filectime($filename);

        if($time !== $cache['nomess_filectime']){
            return FALSE;
        }

        unset($cache['nomess_filectime']);

        return TRUE;
    }

    private function getRefreshCache(string $context, string $classname): array
    {
        if($context === '__UPDATE'){
            return $this->container->get(UpdateBuilder::class)->buider($classname);
        }

        return $this->container->get(SelectBuilder::class)->builder($classname);
    }
}
