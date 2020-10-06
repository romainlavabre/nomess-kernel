<?php


namespace Nomess\Installer;


use Nomess\Component\Config\ConfigStoreInterface;

/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
class InstallerHandler implements InstallerHandlerInterface
{

    private array $package = [];
    
    public function __construct(ConfigStoreInterface $configStore)
    {
        $config = $configStore->get( ConfigStoreInterface::DEFAULT_NOMESS)['packages'];
        
        if(is_array( $config)){
            foreach($config as $packageName => $installer){
                $this->package[$packageName] = new $installer($configStore);
            }
        }
    }
    
    
    /**
     * @inheritDoc
     */
    public function getPackages(): array
    {
        return $this->package;
    }
    
    
}
