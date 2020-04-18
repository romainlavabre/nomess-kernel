<?php

namespace NoMess\Service;

class Tools {

    /**
     * Récupère une valeur du tableau par la clé
     *
     * @param mixed $key La clé recherché 
     * @param array $array Le tableau sujet
     * @return mixed
     */
    public static function arrayByKey(string $key, array $array)
    {
        foreach($array as $keyArray => $value){
            if($keyArray === $key){
                return $value;
            }
        }
    
        return null;
    }

    /**
     * Recherche une valeur par valeur dans le tableau cible $tab   
     *
     * @param mixed $value La valeur rechérché 
     * @param array $tab Le tableau cible
     * @param string $method Si il s'agit d'un objet, la method ex: "getId"
     * @return mixed
     */
    public static function arrayByValue(string $value, array $tab, ?string $method = null)
    {
        foreach($tab as $value2){

            if($method !== null){
                if(trim($value2->$method()) === trim($value)){
                    return $value2;
                }
            }else{
                if($value === $value2){
                    return $value2;
                }
            }
        }
    
        return null;
    }

    /**
     * Cherche une valeur dans un string compris entre 2 délimiteurs
     *
     * @param string $str Chaine recherché
     * @param string $startDel Le déllimiteur
     * @param string|null $endDel Si le premier et deuxième délimiteur sont différent, sinon null
     * @param integer $nbrOc Nombre de délimiteur (par pair) à trouver avant de chercher et retourner la valeur (si elle existe)
     * @return string|null
     */
    public static function searchStrByDelim(string $str, string $startDel, ?string $endDel = NULL, int $nbrOc = 1) : ?string 
    {

        if(strpos($str, $startDel) === 0){
            if($startDel !== 'a'){
                $str = 'a' . $str;
            }else{
                $str = 'b' . $str;
            }
        }

        $tabStr = explode($startDel, $str);
        $tabControl = str_split($str);


        if($endDel === NULL){

            $del1 = 0;

            for($i = 0; $i < count($tabControl); $i++){
                if($tabControl[$i] === $startDel){
                    $del1++;
                }
            }

            if($del1 / 2 !== $nbrOc){
                return null;
            }

            if($nbrOc === 1){
                return $tabStr[1] ? $tabStr[1] : null;
            }else{
                return $tabStr[($nbrOc * 2) - 1] ? $tabStr[($nbrOc * 2) - 1] : null;
            }
        }else{
            $del1 = 0;
            $del2 = 0;

            for($i = 0; $i < count($tabControl); $i++){
                if($tabControl[$i] === $startDel){
                    $del1++;
                }else if($tabControl[$i] === $endDel){
                    $del2++;
                }
            }

            if($del1 !== $nbrOc || $del2 !== $nbrOc){
                return null;
            }

            $tabContent = explode($endDel, $tabStr[$nbrOc]);

            if(strlen($tabContent[0]) === strlen($tabStr[$nbrOc])){
                return null;
            }


            return $tabContent[0] ? $tabContent[0] : null;
        }
    }

    /**
     * Supprime un caractère de la chaîne
     *
     * @param string $char Le caractère à supprimer
     * @param string $str La chaîne sujette
     * @param integer $nbrOc Le nombre de valeur $char trouvé avant de supprimer ex: Supprimer la huitième virgule (seulement)
     * @param boolean $byStart Si il faut commencer par le debut ex: Supprimer la huitième virgule (seulement) depuis la fin de $str
     * @return string
     */
    public static function rmCharByStr(string $char, string $str, int $nbrOc = 1, bool $byStart = TRUE) : string 
    {
        $tabChar = str_split($str);
        $j = 1;

        if($byStart === TRUE){
            for($i = 0; $i < count($tabChar); $i++){
                if($tabChar[$i] === $char){
                    if($nbrOc === $j){
                        $tabChar[$i] = "";
                        break;
                    }else{
                        $j++;
                    }
                }
            }
        }else{
            for($i = count($tabChar) - 1; $i > 0; $i--){
                if($tabChar[$i] === $char){
                    if($nbrOc === $j){
                        $tabChar[$i] = "";
                        break;
                    }else{
                        $j++;
                    }
                }
            }
        }

        return implode($tabChar);
    }

    /**
     * Interne à copyDirRecursive
     *
     * @param string $path
     * @param array $tab
     * @return boolean
     */
    private static function controlDir(string $path, array $tab) : bool 
    {
        foreach($tab as $value){
            if($value === $path){
                return true;
            }
        }

        return false;
    }


    /**
     * Copie|colle l'enssemble d'un dossier de manière récursive et en conservant l'arboréscance 
     *
     * @param string $pathDirSrc Le dossier source ex: racine/monDossier/ | ici, monDossier/* sera collé comme suit: monDossier/*
     * @param string $pathDirDest Le dossier de déstination
     * @return void 
     */
    public static function copyDirRecursive(string $pathDirSrc, string $pathDirDest) : void 
    {
    
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
                        if(!self::controlDir($dir . $tabGeneral[$i] . '/', $tabDirWait)){
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
    
        $tabDest = explode('/', $pathDirSrc);
    
        foreach($tabDirWait as $valDir){
    
            $tabSrc = explode('/', $valDir);
    
            $racSrc = null;
            $findSrc = false;
            foreach($tabSrc as $valSrc){
    
    
                if($tabDest[count($tabDest) - 2] === $valSrc){
                    $racSrc = $valSrc;
                    $findSrc = true;
                }else if($findSrc === true){
                    $racSrc = $racSrc . '/' . $valSrc;
                }
            }
    
            @mkdir($pathDirDest . $racSrc, 0777, true);
    
            $newPath = $pathDirDest . $racSrc . '/';
    
            $tabToCopy = scandir($valDir);
    
            foreach($tabToCopy as $value){
                if(!is_dir($valDir . $value) && $value !== '.' && $value !== '..'){
                    if(copy($valDir . $value, $newPath . $value)){
                        echo "Copie de " . $valDir . $value . " vers " . $pathDirDest . $value . "...\n";
                    }else{
                        echo "Echec: Le fichier " . $valDir . $value . " n'a pas pu être copié vers " . $pathDirDest . $value . "\n";
                    }
                }
            }
        }
    }

    /**
     * Ajoute un élément enfant dans un fichier xml
     *
     * @param SimpleXMLElement $file Le fichier xml cible
     * @param string|null $path Les noeuds à passer pour arriver à l'enfant cible
     * @param string $child La balise enfant 
     * @param string|null $value La valeur à lui attribuer
     * @param array|null $attribute Les attributs de $child ex: array('attribut' => 'value')
     * @return SimpleXMLElement
     */
    public static function addToXML(\SimpleXMLElement $file, ?string $path, string $child, ?string $value, ?array $attribute) : \SimpleXMLElement
    {
        if(!is_null($value)){
            if(!is_null($path)){
                $file->$path->addChild($child, $value);
            }else{
                $file->addChild($child, $value);
            }
        }else{
            $file->addChild($child, "");
        }
    
        if(!is_null($attribute)){
            if(!is_null($path)){
                $path = $path . '->' . $child;
            }else{
                $path = $child;
            }
    
            foreach($attribute as $key => $value){
                $file->$path->addAttribute($key, $value);
            }
        }
    
        return $file;
    }

    /**
     * Ecrase un fichier Xml existant en le remplacant par sa valeur
     *
     * @param SimpleXMLElement $file
     * @param mixed $value
     * @return SimpleXMLElement
     */
    public static function updateToXML(\SimpleXMLElement $file, $value) : \SimpleXMLElement
    {
        $file = $value;
    
        return $file;
    }

    /**
     * Organise les balise d'un fichier xml
     *
     * @param SimpleXMLElement $xml
     * @param string $path
     * @return void
     */
    public static function formatterXML(\SimpleXMLElement $xml, string $path) : void
    {
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML()); // $xml est mon objet en provenance de SimpleXML !
        $formatedXML = $dom->saveXML();
    
        $h = fopen($path, 'w+');
        fwrite($h, $formatedXML);
        fclose($h);
    }


    /**
     * Ajoute un valeur juste avant un délémiteur
     *
     * @param string $path Chemin vers le fichier
     * @param string $data Données à ajouter
     * @param string $delim Délimteur
     * @return boolean
     */
    public static function addAppendDelim(string $path, string $data, string $delim): bool 
    {
        if($file = file($path)){
            for($i = count($file) - 1; $i > 0; $i--){
    
                if(trim($file[$i]) === $delim){
                    $file[$i - 1] = $file[$i - 1] . $data;
                    if(file_put_contents($path, $file)){
                        return true;
                        break;
                    }else{
                        return false;
                        break;
                    }
                }
            }
        }else{
            return false;
        }
    
        return false;
    }

}

