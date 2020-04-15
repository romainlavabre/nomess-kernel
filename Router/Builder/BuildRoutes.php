<?php

namespace NoMess\Router\Builder;

class BuildRoutes{

    /**
     * Chemin vers les controllers
     */
    const DIR = ROOT . 'App/src/Controllers/';


    /**
     * Chemin d'enregistrement du cache
     *
     * @var string
     */
    private $pathRegister;

    /**
     * Contient les instance de controller
     *
     * @var array[DataController]
     */
    private $tabControllers;


    public function __construct(string $path)
    {
        $this->pathRegister = $path;
    }


    /**
     * Enregistre le contenu en cache
     *
     * @return void
     */
    public function build()
    {
        $this->reflector();
        $content = $this->getContent();

        if(!file_put_contents($this->pathRegister, $content)){
            throw new WorkException("Impossible d'enregistrer le build en cache");
        }

        return;
    }


    /**
     * Construit le fichier xml
     *
     * @return string
     */
    private function getContent() : string
    {
        $content = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n\n<config>\n";

        foreach($this->tabControllers as $value){

            $content = $content . "\t<routes url=\"" . $value->getRoute() . "\">\n\t\t<controller>" . $value->getNamespace() . "</controller>\n\t\t<path>" . $value->getPath() . "</path>\n\t\t<auth>" . $value->getAuth() . "</auth>\n\t</routes>\n";
        }

        $content = $content . "</config>";

        return $content;

    }

    /**
     * Visite les controller avec l'api de réfléxivité
     *
     * @return void
     */
    private function reflector() : void
    {

        $dir = $this->scanRecursive();

        foreach($dir as $pathDir){

            $tabContent = scandir($pathDir);

            foreach($tabContent as $file){

                $dataController = new DataController();
                
                if(@is_file($pathDir . $file)){
                    
                    $dataController->setPath($pathDir . $file);
                    $dataController->setNamespace($this->getNamespace($dataController->getPath()));

                    $refle = new \ReflectionClass($dataController->getNamespace());
                    $docComment = $refle->getDocComment();

                    $comment = explode('*', $docComment);

                    foreach($comment as $line){

                        if(strpos($line, '@Route')){

                            $floorOne = explode('"', $line);

                            $dataController->setRoute($floorOne[1]);
                        }

                        if(strpos($line, '@auth')){
                            $floorOne = explode('"', $line);

                            $dataController->setAuth($floorOne[1]);
                        }
                    }

                    $this->tabControllers[] = $dataController;
                }
            }
        }
    }

   
    /**
     * Récupère l'arborescance du dossier App/src/Controllers
     *
     * @return array
     */
    public function scanRecursive() : array
    {
        $pathDirSrc = self::DIR;
        
        $tabGeneral = scandir($pathDirSrc);
    
        $tabDirWait = array();
    
        $dir = $pathDirSrc;
    
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

        return $tabDirWait;
    }

    /**
     * Liée à scanRecursive
     *
     * @param string $path
     * @param array $tab
     * @return boolean
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

    private function getNamespace(string $path) : string
    {
        if(@$file = file($path)){
            foreach($file as $line){
                $exist = strpos($line, 'namespace');

                if($exist !== false){
                    $floorOne = explode(' ', $line);
                    $floorTwo = explode(';', $floorOne[1]);

                    $tabPath = explode('/', $path);
                    $tabPathOne = explode('.', $tabPath[count($tabPath) - 1]);

                    return $floorTwo[0] . '\\' . $tabPathOne[0];
                }
            }

            throw new WorkException('Le namespace de la class n\'a pas été résolue dans le fichier ' . $path . ' pour la method BuildRoutes::getClassName');
        }else{
            throw new WorkException('Le fichier ' . $file . ' n\'a pas été résolue dans la method BuildRoutes::getClassName');
        }
    }
}

class DataController
{

    private $namespace;

    private $path;

    private $route;

    private $auth;

    /**
     *
     * @return string
     */
    public function getNamespace() : string
    {
        return $this->namespace;
    }

    /**
     *
     * @param string $setter
     * @return void
     */
    public function setNamespace(?string $setter) : void
    {
        if(empty($setter)){
            throw new WorkException('Le namespace de la class n\'a pas été résolue dans le fichier ' . $this->path . ' pour la method BuildRoutes::getClassName');
        }

        $this->namespace = $setter;
    }

    /**
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     *
     * @param string $setter
     * @return void
     */
    public function setPath(string $setter) : void
    {
        $this->path = $setter;
    }
 
    /**
     *
     * @return string
     */
    public function getRoute() : string
    {
        return $this->route;
    }

    /**
     *
     * @param string $setter
     * @return void
     */
    public function setRoute(string $setter) : void
    {
        $this->route = $setter;
    }

    public function getAuth() : ?string
    {
        return $this->auth;
    }

    public function setAuth(string $setter) : void
    {
        $this->auth = $setter;
    }
}