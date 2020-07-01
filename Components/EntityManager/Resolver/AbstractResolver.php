<?php


namespace Nomess\Components\EntityManager\Resolver;


use Nomess\Annotations\Inject;
use Nomess\Components\EntityManager\Builder\SelectBuilder;
use Nomess\Components\EntityManager\Builder\UpdateBuilder;
use Nomess\Container\Container;

abstract class AbstractResolver
{

    private const CACHE             = ROOT . 'var/cache/em/';

    private array $calledCache = array();

    /**
     * @Inject()
     */
    protected Container $container;

    protected function getCache(string $classname, string $fullClassname): ?array
    {
        if(!array_key_exists($classname, $this->calledCache)) {

            $filename = self::CACHE . $this->getPrefix() . str_replace('\\', '_', $fullClassname) . '.php';

            if(file_exists($filename)) {
                $cache = require $filename;
                $cache = unserialize($cache);

                if($this->validConsistencyCache($fullClassname, $cache)) {
                    $this->calledCache[$classname] = $cache;
                    return $cache;
                }
            }

            $cache = $this->getBuilder()->builder($fullClassname);
            $this->setCache($cache, new \ReflectionClass($fullClassname));
            return $cache;
        }else{
            return $this->calledCache[$classname];
        }
    }

    private function setCache(array $cache, \ReflectionClass $reflectionClass): void
    {
        $cache['nomess_filectime'] = filectime($reflectionClass->getFileName());

        file_put_contents(self::CACHE . $this->getPrefix() . str_replace('\\', '_', $reflectionClass->getName()) . '.php', '<?php return \'' . serialize($cache) . '\';');
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

    protected function getShortenName(string $classname): string
    {
        return substr(strrchr($classname, '\\'), 1);
    }

    protected function getTable(array &$data): string
    {
        $table = $data['nomess_table'];
        unset($data['nomess_table']);
        return $table;
    }

    private function getPrefix(): string
    {
        if($this instanceof UpdateResolver ){
            return '__UPDATE__';
        }

        return '__SELECT__';
    }

    private function getBuilder(): object
    {
        if($this instanceof UpdateResolver ){
            return $this->container->get(UpdateBuilder::class);
        }

        return $this->container->get(SelectBuilder::class);
    }

}
