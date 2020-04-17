<?php 

namespace NoMess\DataManager\Builder;

use NoMess\Exception\WorkException;


class BuilderDataManager 
{

    const DIR       = ROOT . 'App/src/Modules/';


    /**
     * Builder
     *
     * @return void
     */
    public function builderManager() : void
    {
        $this->tabDir = $this->getDir();

        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n\n<data>\n";
        foreach($this->tabDir as $path){
            $present = strpos($path, 'Entity');

            if($present !== false){
                $tabFile = scandir($path);

                
                foreach($tabFile as $content){
                    
                    if($content !== '.' 
                        && $content !== '..'
                        && is_file($path . $content)){
                            
                        $cls = $this->getComment($this->getNamespace($path . $content) . '\\' . $this->getClassName($path . $content));


                        if($cls !== null){
                            $xml = $xml . "\t<class class=\"" . $cls->getClassName() . "\">\n";

                            if(!empty($cls->getKey())){
                                $xml = $xml . "\t\t<session>\n";
                                $xml = $xml . "\t\t\t<key>" . $cls->getKey() . "</key>\n";
                                
                                if(!empty($cls->getKeyArray())){
                                    $xml = $xml . "\t\t\t<keyArray>" . $cls->getKeyArray() . "</keyArray>\n";
                                }

                                if(!empty($cls->getDbDepend())){
                                    foreach($cls->getSesDepend() as $key => $value){
                                        $tabValue = explode('::', $key);

                                        $xml = $xml . "\t\t\t<depend class=\"" . $tabValue[0] .  "\" set=\"" . $value . "\" get=\"" . $tabValue[1] . "\"/>\n";
                                    }
                                }

                                $xml = $xml . "\t\t</session>\n";
                            }

                            if(!empty($cls->getBase())){
                                $xml = $xml . "\t\t<base class=\"" . $cls->getBase() . "\">\n";
                                
                                if(!empty($cls->getInsert())){
                                    $xml = $xml . "\t\t\t<insert>" . $cls->getInsert() . "</insert>\n";
                                }

                                if(!empty($cls->getDbDepend())){
                                    foreach($cls->getDbDepend() as $key => $value){
                                        $tabValue = explode('::', $key);

                                        $xml = $xml . "\t\t\t<depend class=\"" . $tabValue[0] .  "\" set=\"" . $value . "\" get=\"" . $tabValue[1] . "\"/>\n";
                                    }
                                }

                                if(!empty($cls->getNoTransaction())){

                                    foreach($cls->getNoTransaction() as $key => $value){
                                        $noTransactionContent = "\t\t\t<transaction ";
                                        $noTransactionContent = $noTransactionContent . "name=\"" . $key . "\" ";


                                        foreach($value as $key => $no){
                                            $noTransactionContent = $noTransactionContent . $no . "=\"false\" " ;
                                        }

                                        $noTransactionContent = $noTransactionContent . "/>\n";
                                        $xml = $xml . $noTransactionContent;
                                    }
                                }

                                if(!empty($cls->getAlias())){
                                    foreach($cls->getAlias() as $key => $value){
                                        $alias = "\t\t\t<alias method=\"" . $key . "\" alias=\"" . $value . "\"/>\n";
                                        $xml = $xml . $alias;
                                    }
                                }

                                $xml = $xml . "\t\t</base>\n";
                            }


                             $xml = $xml . "\t</class>\n";
                        }
                    }
                }
            }
        }

        $xml = $xml . "</data>";

        if(!file_put_contents('App/var/cache/mondata.xml', $xml)){
            throw new WorkException('MonitoringData: Impossible d\'accéder au cache');
        }
    }


    /**
     *
     * @return array|null
     */
    private function getDir() : ?array
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
     *
     * @param string $path
     * @return string
     */
    private function getClassName(string $path) : string
    {
        if(@$file = file($path)){
            foreach($file as $line){
                $exist = strpos($line, 'class');

                if($exist !== false){
                    
                    $tab = explode(' ', $line);

                    $i = 0;

                    foreach($tab as $value){
                        if($value === 'class'){
                            return $tab[$i + 1];
                        }

                        $i++;
                    }
                }
            }

            throw new WorkException('Le nom de la class n\'a pas été résolue dans le fichier ' . $path . ' pour la method BuildRoutes::getClassName');
        }else{
            throw new WorkException('Le fichier ' . $file . ' n\'a pas été résolue dans la method BuildRoutes::getClassName');
        }
    }


    /**
     *
     * @param string $path
     * @return string|null
     */
    private function getNamespace(string $path) : ?string
    {
        if(@$file = file($path)){
            foreach($file as $line){
                $exist = strpos($line, 'namespace');

                if($exist !== false){
                    
                    $floorOne = explode(' ', $line);
                    $floorTwo = explode(';', $floorOne[1]);
                    return $floorTwo[0];
                }
            }
        }else{
            throw new WorkException('Le fichier ' . $file . ' n\'a pas pu être ouvert dans la method MonitoringData::getNamespace');
        }
    }

    private function getComment(string $className) : ?DocComment
    {
        $util = false;
        
        $reflection = new \ReflectionClass($className);
        
        $cls = new DocComment($className);
        
        $comment = $reflection->getDocComment();

        $tabHead = explode('*', $comment);
        
        foreach($tabHead as $value){

            if(strpos($value, '@session') !== false){
                $floorOne = explode('"', $value);
                
                $cls->setKey($floorOne[1]);
                $util = true;
            }

            if(strpos($value, '@database')){
                $floorOne = explode('"', $value);
                
                if(count($floorOne) <= 3){
                    $cls->setBase($floorOne[1]);
                    $util = true;
                }else{
                    if(isset($floorOne[3])){
                        $cls->setAlias($floorOne[1], $floorOne[3]);
                    }else{
                        throw new WorkException('Erreur de syntaxe dans les commantaire de la class' . $className);
                    }
                }
            }
        }

        $noTransaction = array();


        foreach($reflection->getProperties() as $value){

            $line = explode('*', $value->getDocComment());


            foreach($line as $propComment){
                $floorOne = explode('"', $propComment);


                $get = null;
                $set = null;

                if($value->isPrivate() || $value->isProtected()){
                    $get = 'get' . ucfirst($value->getName());
                    $set = 'set' . ucfirst($value->getName());
                }else{
                    $get = $value->getName();
                    $set = $value->getName();
                }

                if(strpos($propComment, '@session') !== false){


                    if($floorOne[1] === 'keyArray'){
                        $cls->setKeyArray($get);
                        $util = true;
                    }

                    if($floorOne[1] === 'depend'){
                        $get = $floorOne[3];
                        $cls->setSesDepend($get, $set);
                        $util = true;
                    }
                    
                }

                if(strpos($propComment, '@database')){

                    if($floorOne[1] === 'insert'){
                        $cls->setInsert($set);
                        $util = true;
                    }

                    if($floorOne[1] === 'depend'){
                        $get = $floorOne[3];
                        $cls->setDbDepend($get, $set);
                        $util = true;
                    }

                    if($floorOne[1] === 'noCreate'){
                        $noTransaction[$value->getName()][] = 'noCreate';
                    }

                    if($floorOne[1] === 'noUpdate'){
                        $noTransaction[$value->getName()][] = 'noUpdate';
                    }

                    if($floorOne[1] === 'noDelete'){
                        $noTransaction[$value->getName()][] = 'noDelete';
                    }
                }
            }
        }

        $cls->setNoTransaction($noTransaction);

        if($util === true){

            if((!empty($cls->getSesDepend()) || !empty($cls->getKeyArray())) && empty($cls->getKey())){
                throw new WorkException('Clé de session attendu pour ' . $cls->getClassName());
                die;
            }

            if((!empty($cls->getDbDepend()) || !empty($cls->getInsert())) && empty($cls->getBase())){
                throw new WorkException('Table de persistance attendu pour ' . $cls->getClassName());
                die;
            }
            
            
            return $cls;
        }

        return null;
    }
}