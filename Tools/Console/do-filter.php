<?php

require 'function-Installer.php';

(new Filter())->generate();

class Filter
{
    private const PATH              = 'src/Filters/';


    public function generate()
    {
        do{
            $filtername = rdl("Precise the name of filter: ");
        }while($filtername === NULL);

        file_put_contents(self::PATH . ucfirst($filtername) . '.php', $this->getContent($filtername));

        echo 'Filter generate';
    }

    private function getContent(string $name): string
    {
        return "<?php

namespace App\Filters;

use Nomess\Annotations\Filter;
use Nomess\Manager\FilterInterface;

/**
 * @Filter(\"your_regex_here\")
 */
class " . ucfirst($name) . " implements FilterInterface
{
    
    public function filtrate(): void
    {
        /* 
         * TODO create your rule
         *  You can use the dependency injection
         *  Use ResponseHelper for send an response
         */
    }
}";
    }
}
