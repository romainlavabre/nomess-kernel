<?php


namespace Nomess\Components\EntityManager\EntityCache;


use Nomess\Annotations\Inject;
use Nomess\Components\EntityManager\EntityManagerInterface;

class Dependency
{
    
    private const PATH_CACHE_EM = ROOT . 'var/cache/em/';
    
    private array $calledCache     = array();
    private array $propertyVisited = array();
    /**
     * @Inject()
     */
    private EntityManagerInterface $entityManager;
    /**
     * @Inject()
     */
    private Repository $repository;
    
    
    /**
     * Visite object for replace all instance by consistency instance
     *
     * @param object $object
     */
    public function pushDependency( object $object ): void
    {
        $classname = get_class( $object );
        $cache     = $this->getCacheEntityManager( $classname );
        
        foreach( $cache as $propertyColumn => $propertyData ) {
            
            if( $propertyColumn !== 'nomess_table'
                && $propertyColumn !== 'nomess_filectime'
                && isset( $propertyData['relation'] ) && !empty( $propertyData['relation'] ) ) {
                
                $reflectionProperty = $this->getReflectionProperty( $classname, $propertyData['name'] );
                
                $reflectionProperty->setAccessible( TRUE );
                
                $valueProperty = NULL;
                
                if( $reflectionProperty->isInitialized( $object ) ) {
                    $valueProperty = $reflectionProperty->getValue( $object );
                }
                
                if( !empty( $valueProperty ) ) {
                    if( is_array( $valueProperty ) ) {
                        $list = array();
                        
                        $function = function ( array $array ) {
                            
                            $list = array();
                            
                            foreach( $array as $key => $data ) {
                                if( is_object( $data ) ) {
    
                                    $result = $this->getDependency( $data );
                                    
                                    if( $result !== FALSE ) {
                                        $list[$key] = $result;
                                    }
                                } elseif( is_array( $data ) ) {
                                    $list[$key] = $function( $data );
                                }
                            }
                            
                            return $list;
                        };
                        
                        $reflectionProperty->setValue( $object, $function( $valueProperty ) );
                    } elseif( is_object( $valueProperty ) ) {
                        $result = $this->getDependency( $valueProperty );
                        
                        if( $result !== FALSE ) {
                            $reflectionProperty->setValue( $object, $result );
                        }
                    }
                }
            }
        }
    }
    
    
    /**
     * Return an complete object by store or entityManager
     *
     * @param object $dependency
     * @return object|bool|null
     */
    private function getDependency( object $dependency )
    {
        $classname = get_class( $dependency );
        $id = $dependency->getId();
        
        if( method_exists( $dependency, 'getId' ) ) {
            if( $this->repository->storeHas($classname, $id) ) {
                return $this->repository->getToStore($classname, $id, Repository::ENTITY);
            }
            
            $this->entityManager->find( $classname, $id );
            
            if( $this->repository->storeHas($classname, $id) ) {
                return $this->repository->getToStore($classname, $id, Repository::ENTITY);
            }
            
            return NULL;
        } else {
            return FALSE;
        }
        
        return NULL;
    }
    
    
    /**
     * Return the associate cache to classname (in __SELECT__ mode) building
     * by entityManager
     *
     * @param string $classname
     * @return array
     */
    private function getCacheEntityManager( string $classname ): array
    {
        if( isset( $this->calledCache[$classname] ) ) {
            return $this->calledCache[$classname];
        }
        
        $cache = unserialize( require self::PATH_CACHE_EM . '__SELECT__' . str_replace( '\\', '_', $classname ) . '.php' );
        
        return $this->calledCache[$classname] = $cache;
    }
    
    
    private function getReflectionProperty( string $classname, string $propertyname ): \ReflectionProperty
    {
        if( isset( $this->propertyVisited[$classname][$propertyname] ) ) {
            return $this->propertyVisited[$classname][$propertyname];
        }
        
        return $this->propertyVisited[$classname][$propertyname] = new \ReflectionProperty( $classname, $propertyname );
    }
}
