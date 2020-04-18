<?php

namespace NoMess\Web\Builder;

use Exception;
use SimpleXMLElement;

class WebBuilder 
{
    private const PATH_CACHE            = ROOT . 'Web/cache/webRouter/template.php';
    private const PATH_CONFIG           = ROOT . 'Web/template.xml';


    /**
     * Contenu du fichier de configuration
     *
     * @var SimpleXMLElement
     */
    private $file;

    /**
     * Contient les routes
     *
     * @var array[controller:method/true||false][template]
     *                  
     */ 
    private $webRoute;


    /**
     *
     * @throws Exception
     * @return void
     */
    public function webBuilder() : void
    {
        $this->getConfig();
        $this->castToArray();

        if(!file_put_contents(self::PATH_CACHE, $this->createContent())){
            throw new Exception('Impossible d\'enregistrer le fichier de cache');
        }
    }


    /**
     * Retourne le fichier de configuration
     * 
     * @throws Exception
     * @return SimpleXMLElement
     */
    private function getConfig() : void
    {
        if(@!$this->file = simplexml_load_file(self::PATH_CONFIG)){
            throw new Exception('Impossible de localiser template.xml');
        }
    }

    /**
     * Créer le tableau de routes
     *
     * @throws Exception
     * @return void
     */
    private function castToArray() : void
    {
        foreach($this->file->template as $value){
            foreach($value->true as $true){
                if(!isset($this->webRoute[(string)$true . '/true'])){
                    $this->webRoute[strtolower((string)$true) . '/true'] = (string)$value->attributes()['name'];
                }else{
                    throw new Exception('Builder WebRoute conflict: La signature \"' . $this->webRoute[(string)$true . '/true'] . '\" est mentionnée deux fois');
                }
            }

            foreach($value->false as $false){

                if(!isset($this->webRoute[(string)$false . '/false'])){
                    $this->webRoute[strtolower((string)$false) . '/false'] = (string)$value->attributes()['name'];
                }else{
                    throw new Exception('Builder WebRoute conflict: La signature \"' . $this->webRoute[(string)$false . '/false'] . '\" est mentionnée deux fois');
                }
            }
        }
    }

    private function createContent() : string
    {
        $content = "<?php\nreturn [\n";

        foreach($this->webRoute as $key => $value){
            $content .= "\t'" . $key . "' => '" . $value . "',\n";
        }

        $content .= "];";

        return $content;
    }

}