<?php


namespace Nomess\Components\EntityManager\EntityCache;


use Nomess\Components\EntityManager\EntityManager;
use Nomess\Components\EntityManager\Resolver\Instance;
use Nomess\Components\EntityManager\TransactionObserverInterface;
use Nomess\Components\EntityManager\TransactionSubjectInterface;

class CacheManager implements TransactionObserverInterface
{
    
    private TransactionSubjectInterface $transactionSubject;
    private Repository                  $repository;
    private Writer                      $writer;
    
    
    /**
     * * @param EntityManager $entityManager
     * @param TransactionSubjectInterface $transactionSubject
     */
    public function __construct(
        EntityManager $entityManager,
        TransactionSubjectInterface $transactionSubject,
        Repository $repository,
        Writer $writer )
    {
        $this->transactionSubject = $transactionSubject;
        $this->repository         = $repository;
        $this->writer             = $writer;
        
        $this->revalide();
        $this->subscribeToTransactionStatus();
    }
    
    
    /**
     * Return all entity for this classname
     *
     * @param string $classname
     * @param bool $lock
     * @return array|null
     */
    public function getAll( string $classname, bool $lock = FALSE ): ?array
    {
        if( $this->repository->isAllSelected( $classname ) ) {
            
            $list = array();
            
            $content = scandir( Repository::PATH_CACHE );
            
            if( !empty( $content ) ) {
                foreach( $content as $file ) {
                    if( $this->repository->getClassnameByFilename( $file ) === $classname ) {
                        $list[] = $this->get(
                            $classname,
                            $this->repository->getIdByFilename( $file ),
                            FALSE,
                            $lock );
                    }
                }
            }
            
            return $lock ? NULL : $list;
        }
        
        return NULL;
    }
    
    
    public function get( string $classname, int $id, bool $bean = FALSE, bool $lock = FALSE ): ?object
    {
        if( $lock ) {
            $this->repository->removeFile( $this->repository->getFilename( $classname, $id ) );
            
            return NULL;
        }
        
        if( $this->repository->storeHas( $classname, $id ) ) {
            return $bean ? $this->repository->getToStore( $classname, $id, Repository::BEAN )
                : $this->repository->getToStore( $classname, $id, Repository::ENTITY );
        }
    
        if( file_exists( $filename = $this->repository->getFilename( $classname, $id ) ) ) {
            $this->repository->addInStore( unserialize( file_get_contents( $filename ) ) );
           
            if( $bean ) {
                return $this->repository->getToStore( $classname, $id, Repository::BEAN );
            } else {
                return $this->repository->getToStore( $classname, $id, Repository::ENTITY );
            }
        }
        
        return NULL;
    }
    
    
    public function addAll( string $classname ): void
    {
        $this->repository->addSelectAll( $classname );
    }
    
    
    public function add( object $object ): void
    {
        foreach( Instance::$mapper[get_class( $object )] as $data ) {
            
            if( $data['object'] === $object ) {
                $this->repository->addInStore( $data );
            }
        }
    }
    
    
    /**
     * Delete instance and push her filename in to remove, if transaction is commited,
     * file is deleted
     *
     * @param object $object
     */
    public function remove( object $object ): void
    {
        $this->repository->addRemoved( $object );
    }
    
    
    /**
     * When property is complete, the CacheManager is notified and he clone it.
     * Object is cloned in anticipation is not notified by entityManager, if that is the case,
     * the selected object is written by her clone for consistency data
     *
     * @param object $object
     */
    public function clonable( object $object ): void
    {
        foreach( Instance::$mapper[get_class( $object )] as $data ) {
            
            if( $data['object'] === $object ) {
                
                $this->repository->addClone( $data );
            }
        }
    }
    
    
    private function revalide(): void
    {
        
        if( $this->repository->mustRevalideCache() ) {
            foreach( $this->repository->scanCache() as $filename ) {
                $this->repository->removeFile( $filename );
            }
            
            $this->repository->resetStatus();
        }
    }
    
    
    public function subscribeToTransactionStatus(): void
    {
        $this->transactionSubject->addSubscriber( $this );
    }
    
    
    public function statusTransactionNotified( bool $status ): void
    {
        $this->writer->writerNotifiedEvent( $status );
    }
    
    
    public function __destruct()
    {
        $this->writer->writerDestructEvent();
    }
}
