<?php


namespace Nomess\Components\EntityManager\EntityCache;


use Nomess\Annotations\Inject;

class Writer
{
    
    /**
     * @Inject()
     */
    private Repository $repository;
    private bool $isNotified = FALSE;
    
    
    public function writerNotifiedEvent( bool $status ): void
    {
        $this->isNotified = TRUE;
        
        if( $status === TRUE ) {
            foreach( $this->repository->getStore() as $classname => $data ) {
                foreach( $data as $id => $objBean ) {
                    $this->writeData( $classname, $id, $objBean );
                }
            }
            
            foreach( $this->repository->getRemoved() as $filename ) {
                $this->repository->removeFile( $filename );
            }
            
            if( $this->repository->isStatusChanged() ) {
                $this->writeStatus();
            }
        }
    }
    
    
    public function writerDestructEvent(): void
    {
        if( !$this->isNotified ) {
            
            foreach( $this->repository->getCloned() as $classname => $data ) {
                foreach( $data as $id => $objBean ) {
                    $this->writeData( $classname, $id, $objBean );
                }
            }
        }
        
        if( $this->repository->isStatusChanged() ) {
            $this->writeStatus();
        }
    }
    
    
    private function writeData( string $classname, int $id, $data ): void
    {
        file_put_contents( $this->repository->getFilename( $classname, $id ), serialize( $data ), LOCK_EX );
    }
    
    
    private function writeStatus(): void
    {
        file_put_contents( Repository::PATH_STATUS, serialize( $this->repository->getStatus()), LOCK_EX );
    }
}
