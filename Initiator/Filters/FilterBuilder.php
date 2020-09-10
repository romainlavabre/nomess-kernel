<?php


namespace Nomess\Initiator\Filters;


use Nomess\Component\Config\ConfigHandler;
use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Exception\MissingConfigurationException;

class FilterBuilder
{
    private ConfigStoreInterface $configStore;
    
    public function __construct(ConfigStoreInterface $configStore)
    {
        $this->configStore = $configStore;
    }
    
    
    public function build(): array
    {
        $directory = $this->configStore->get(ConfigStoreInterface::DEFAULT_NOMESS)['general']['path']['default_filter'];
        
        if(is_dir($directory)) {
            $filters = scandir( $directory );
            $found   = array();
    
            foreach( $filters as $filter ) {
                if( $filter !== '.' && $filter !== '..' && $filter !== '.gitkeep' ) {
                    $filterName = 'App\\Filter\\' . str_replace( '.php', '', $filter );
                    $regex      = $this->getAnnotation( $filterName );
            
                    $found[$filterName] = $regex;
                }
            }
    
            return $found;
        }
        
        return [];
    }

    /**
     * @param string $classname
     * @return string
     * @throws MissingConfigurationException
     * @throws \ReflectionException
     */
    private function getAnnotation(string $classname): string
    {
        if(preg_match('/@Filter\("(.+)"\)/', (new \ReflectionClass($classname))->getDocComment(), $output)){
            return $output[1];
        }

        throw new MissingConfigurationException('You filter annotation is incomplete in ' . $classname);
    }
}
