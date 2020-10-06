<?php


namespace Nomess\Installer;


use Nomess\Component\Config\ConfigStoreInterface;

/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
interface NomessInstallerInterface
{
    
    public function __construct(ConfigStoreInterface $configStore);
    
    
    /**
     * Return the mapping into interface and class
     *
     * @return array
     */
    public function container(): array;
    
    
    /**
     * Contains the classname of controllers
     *
     * @return array
     */
    public function controller(): array;
    
    
    /**
     * Contains configuration for cli
     * @return ["command_name" => "classname"]
     */
    public function cli(): array;
    
    /**
     * Execute script
     *
     * @return string|null
     */
    public function exec(): ?string;
}
