<?php 

namespace NoMess\DiBuilder;

class DiBuilder
{
    private const DI_DEFINITIONS                = '../App/config/di-definitions.php';
    private const DIRECTORY                     = '../App/src/';

    private const RULE                          = '@autowire';

    /**
     * Contient les dossier a visiter
     *  
     * @var array
     */
    private $directory;

    /**
     * Contient les definitions
     *
     * @var array
     */
    private $definitions;


    /**
     *
     * @return void
     */
    public function diBuilder() : void
    {

        $noBuild = false;

        $file = file(self::DI_DEFINITIONS);

        foreach($file as &$line){
            if(strpos($line, 'START NOMESS DI-BUILDER') !== false){
                $noBuild = true;
            }
        }



        if($noBuild ===  false){
            $this->readDirectory();
            $this->fileParser();

            $content = "/**\n\t * =============================== START NOMESS DI-BUILDER ================================= \n\t */\n";

            if($this->definitions !== null){
                foreach($this->definitions as $value){
                    $content .= "\t" . $value . "::class => DI\autowire(),\n\n";
                }

                $content .= "\t/**\n\t * =============================== END NOMESS DI-BUILDER ================================== \n\t */\n\n\n";


                foreach($file as &$line){
                    if(strpos($line, 'return [') !== false){
                        $line .= "\n\n\t" . $content;
                    }
                }


                file_put_contents(self::DI_DEFINITIONS, $file);
            }
        }
    }


    /**
     * Rapporte l'arboréscance des dossier à visiter
     *
     * @return void
     */
    private function readDirectory() : void
    {
        $tabGeneral = scandir(self::DIRECTORY);

        $tabDirWait = array();

        $dir = self::DIRECTORY;

        $noPass = count(explode('/', $dir));

        do{
            $stop = false;

            do{
                $tabGeneral = scandir($dir);
                $dirFind = false;

                for($i = 0; $i < count($tabGeneral); $i++){
                    if(is_dir($dir . $tabGeneral[$i] . '/') && $tabGeneral[$i] !== '.' && $tabGeneral[$i] !== '..'){
                        if(!$this->controlDir($dir . $tabGeneral[$i] . '/', $tabDirWait)){
                            $dir = $dir . $tabGeneral[$i] . '/';
                            $dirFind = true;
                            break;
                        }
                    }
                }

                if(!$dirFind){
                    $tabDirWait[] = $dir;
                    $tabEx = explode('/', $dir);
                    unset($tabEx[count($tabEx) - 2]);
                    $dir = implode('/', $tabEx);
                }

                if(count(explode('/', $dir)) < $noPass){
                    $stop = true;
                    break;
                }
            }
            while($dirFind === true);
        }
        while($stop === false);


        $this->directory = $tabDirWait;
    
    }

    /**
     * Interne à read directory
     *
     * @param string $path
     * @param array $tab
     *
     * @return bool
     */
    private function controlDir(string $path, array $tab) : bool
    {
        foreach($tab as $value){
            if($value === $path){
                return true;
            }
        }

	    return false;
    }

    /**
     * Ajoute les definitions 
     *
     * @return void
     */
    private function fileParser() : void
    {

        foreach($this->directory as $directory){
            $content = scandir($directory);

            foreach($content as $value){


                if($value !== '.' && $value !== '..' && is_file($directory . $value)){
                    $namespace = null;
                    $className = null;

                    @$file = file($directory . $value);

                    foreach($file as $line){

                        if(strpos($line, 'namespace') !== false){
                            $namespace = str_replace('namespace ', '', $line);
                            $namespace = str_replace(';', '\\', $namespace);
                        }

                        if(strpos($line, self::RULE)){

                            $className = str_replace('.php', '', $value);
                            $this->definitions[] = trim($namespace) . $className;
                            break;

                        }
                    }
                }
            }
        }
    }
}