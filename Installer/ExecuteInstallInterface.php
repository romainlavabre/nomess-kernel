<?php


namespace Nomess\Installer;


/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
interface ExecuteInstallInterface
{
    
    /**
     * Execute your script.
     * The dependency injection is available for this class
     */
    public function exec(): void;
}
