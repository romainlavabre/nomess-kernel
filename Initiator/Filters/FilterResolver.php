<?php


namespace Nomess\Initiator\Filters;


use Nomess\Component\Cache\CacheHandlerInterface;
use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Container\Container;

class FilterResolver
{
    
    private const CACHE_FILER = 'filters';
    private Container             $container;
    private CacheHandlerInterface $cacheHandler;
    private ConfigStoreInterface  $configStore;
    private FilterBuilder         $filterBuilder;
    
    
    public function __construct(
        Container $container,
        CacheHandlerInterface $cacheHandler,
        ConfigStoreInterface $configStore,
        FilterBuilder $filterBuilder
    )
    {
        $this->container     = $container;
        $this->cacheHandler  = $cacheHandler;
        $this->configStore   = $configStore;
        $this->filterBuilder = $filterBuilder;
    }
    
    
    public function resolve( string $route ): void
    {
        $filters = $this->cacheHandler->get( self::CACHE_FILER, 'filters_match' );
        
        if( $filters === NULL ) {
            $filters = $this->filterBuilder->build();
            $this->cacheHandler->add(self::CACHE_FILER, [
                'value' => $filters
            ]);
        }
        
        foreach( $filters as $filterName => $regex ) {
            if( preg_match( '/' . $regex . '/', $route ) ) {
                $this->container->get( $filterName )->filtrate();
            }
        }
    }
    
}
