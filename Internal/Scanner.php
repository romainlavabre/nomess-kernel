<?php

namespace Nomess\Internal;

trait Scanner
{
    
    /**
     * Return tree of directory 'App/src/Controllers'
     *
     * @param string $dir
     * @return array
     */
    public function scanRecursive( string $dir ): array
    {
        $pathDirSrc = $dir;
        
        $tabGeneral = scandir( $pathDirSrc );
        
        $tabDirWait = array();
        
        $dir = $pathDirSrc;
        
        $noPass = count( explode( '/', $dir ) );
        
        do {
            $stop = FALSE;
            
            do {
                $tabGeneral = scandir( $dir );
                $dirFind    = FALSE;
                
                for( $i = 0, $iMax = count( $tabGeneral ); $i < $iMax; $i++ ) {
                    if( is_dir( $dir . $tabGeneral[$i] . '/' )
                        && $tabGeneral[$i] !== '.'
                        && $tabGeneral[$i] !== '..'
                        && !$this->controlDir( $dir . $tabGeneral[$i] . '/', $tabDirWait ) ) {
                        
                        $dir     .= $tabGeneral[$i] . '/';
                        $dirFind = TRUE;
                        break;
                    }
                }
                
                if( !$dirFind ) {
                    $tabDirWait[] = $dir;
                    $tabEx        = explode( '/', $dir );
                    unset( $tabEx[count( $tabEx ) - 2] );
                    $dir = implode( '/', $tabEx );
                }
                
                if( count( explode( '/', $dir ) ) < $noPass ) {
                    $stop = TRUE;
                    break;
                }
            } while( $dirFind === TRUE );
        } while( $stop === FALSE );
        
        return $tabDirWait;
    }
    
    
    private function controlDir( string $path, array $tab ): bool
    {
        foreach( $tab as $value ) {
            if( $value === $path ) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
}
