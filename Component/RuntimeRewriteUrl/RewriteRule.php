<?php

namespace NoMess\Component\RuntimeRewriteUrl;

use Throwable;
use NoMess\ObserverInterface;
use NoMess\Component\Component;
use NoMess\Exception\WorkException;

class RewriteRule extends Component implements ObserverInterface
{


    /**
     * Contenu des regles
     *
     * @var array
     */
    private $contentRule = array();


    /**
     * Mise à jour
     *
     * @var boolean
     */
    private $updateRule = false;



    public function __construct()
    {
        parent::__construct();
        
        $this->contentRule = require __DIR__ . '/rule.php';
    }



    /**
     * Ajoute une regle de réécriture
     *
     * @param string $search
     * @param string|null $rewrite
     * @param string $id id unique
     *
     * @return void
     */
    public function createRule(callable $search, ?string $rewrite, string $id) : void
    {
        $this->contentRule[$id] = [$this->escape($search()) => $rewrite];
        $this->updateRule = true;
    }


    /**
     * Supprime une regle de réécriture
     *
     * @param string $index
     *
     * @return bool
     */
    public function unsetRule(string $id) : bool
    {
        if(array_key_exists($id, $this->contentRule)){

            unset($this->contentRule[$id]);
            $this->updateRule = true;

            return true;
        }else{
            return false;
        }
    }


    /**
     * Rend compatible avec le composant la chaine à rechercher
     *
     * @param string $search
     *
     * @return void
     */
    public function escape(string $search) : string
    {

        return str_replace("'", "’", $search);
    }



    /**
     * Retourne la paire clé/valeur de l'id spécifié
     *
     * @param string $id
     *
     * @return array|null
     */
    public function getRule(string $id) : ?array
    {

        if(array_key_exists($id, $this->contentRule)){
            return $this->contentRule[$id];
        }else{
            return null;
        }
    }



    /**
     * Retourne toutes les regles de réécriture
     *
     * @return array|null
     */
    public function getRules() : ?array
    {
        return $this->contentRule;
    }




    /**
     * Réécrie les urls
     *
     * @return void
     */
    public function rewrite() : void
    {
        if(!empty($this->contentRule)){

            $update = false;

            $_GET['p'] .= '/';

            foreach($this->contentRule as $key => $value){

                foreach($value as $search => $rewrite){
                    if(strpos($_GET['p'], "/$search/") !== false){
                        $update = true;
                        $_GET['p'] = str_replace("/$search/", "/param/$rewrite/", $_GET['p']);
                    }
                }
            }

            $_GET['p'] = rtrim($_GET['p'], '/');

            if($update === true){
                $sequence = explode('/', $_GET['p']);

                $find = false;

                $i = 0;

                foreach($sequence as $value){

                    if($value === 'param'){

                        if($find === false){
                            $find = true;
                        }else{
                            $sequence[$i] = null;
                        }
                    }

                    $i++;
                }

                $_GET['p'] = implode('/', $sequence);
                $_GET['p'] = str_replace('//', '/', $_GET['p']);

            }

        }

    }



    /**
     * En cas de modification, reconstruit le fichier de regle
     *
     * @return void
     */
    public function persiste() : void
    {

        if($this->updateRule === true){

            $content = 
            "<?php
                \rreturn [\r
            ";

            foreach($this->contentRule as $id => $value){
                foreach($value as $search => $rewrite){
                    $content .= '\'' . $id . '\' => [\'' . $search . '\' => \'' . $rewrite . '\'],' . "\r\t";
                }
            }

            $content .= '];';


            try{
                file_put_contents(__DIR__ . '/rule.php', $content);
            }catch(Throwable $e){
                throw new WorkException('Impossible d\'accéder a rule.php, message:' . $e->getMessage());
            }
        }
    }


    /**
     * Réécrie les valeur de l'url si nécessaire
     *
     * @return void
     */
    public function notifiedInput(): void
    {
        $this->rewrite();
    }


    /**
     * Persiste les modifications des regles de réécriture
     *
     * @return void
     */
    public function notifiedOutput(): void
    {
        $this->persiste();
    }
}