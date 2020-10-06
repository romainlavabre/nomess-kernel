<?php


namespace Nomess\Installer;


/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
interface InstallerHandlerInterface
{
    
    /**
     * @return NomessInstallerInterface[]|[]
     */
    public function getPackages(): array;
}
