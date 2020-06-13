<?php

require 'function-Installer.php';

$generator = new FormGenerator();
$generator->generate();

class FormGenerator
{

    private const PATH_REGISTER         = 'App/src/Forms/';

    public function generate(): void
    {

        $name = null;

        do {
            $name = rdl('Please, entre the name of your form: ');

        }while(empty($name));


        $name = ucfirst($name);
        $engine = null;

        do{

            echo 'Engine:
            1. Twig
            2. Php
            ';

            $engine = rdl('Please, specify your engine: (1 or 2)');
        }while(empty($engine));


        file_put_contents(self::PATH_REGISTER . $name . '.php', $this->create($name, $engine));


        echo 'I have create your form' . "\r";
    }


    private function create(string $name, string $engine): string
    {
        if($engine === '1'){
            $engine = 'self::TWIG_ENGINE';
        }else{
            $engine = 'self::DEFAULT_ENGINE';
        }

        return '<?php
namespace App\Forms;

use NoMess\Components\Forms\AbstractFormBuilder;    
    
class ' . $name . ' extends AbstractFormBuilder
{
    public function describe(): void
    {
        $this->bindEnvironment(' . $engine . ');
        
        $this->makeForm([
            \'action\' => \'action\'
        ]);
        
        /*
            Demo
        */
        $this->startGroup([\'class\' => \'classOne classTwo\']);
        $this->label([\'for\' => \'framework\', \'value\' => \'What\\\'s your favorite framework ?\']);
        $this->input([\'type\' => \'text\', \'value\' => \'nomess !!!!\']);
        $this->endGroup();
        
        $this->endForm();
    }
} 
        
        ';
    }
}