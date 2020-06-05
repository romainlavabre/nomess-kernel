<?php

namespace NoMess\Component\RuntimeRewriteUrl;

use Throwable;
use NoMess\ObserverInterface;
use NoMess\Component\Component;
use NoMess\Exception\WorkException;

class RewriteRule extends Component implements ObserverInterface
{


    /**
     * Contains rules
     */
    private ?array $contentRule = array();


    /**
     * Mise à jour
     */
    private bool $updateRule = false;



    public function __construct()
    {
        parent::__construct();
        
        $this->contentRule = require __DIR__ . '/rule.php';
    }



    /**
     * Add an rewrite rule
     *
     * @param string $search
     * @param string|null $rewrite
     * @param string $id unique id
     *
     * @return void
     */
    public function createRule(callable $search, ?string $rewrite, string $id) : void
    {
        $key = $this->escape($search());
        $key = trim($key);


        $i = 1;

        foreach($this->contentRule as $arrayId => $value){

            if($i === 1 && key($value) === $key && $arrayId !== trim($id)){
                $i++;
            }else if($i > 1 && key($value) === "$key-$i" && $arrayId !== trim($id)){
                $i++;
            }
        }

        if($i > 1){
            $key .= "-$i";
        }
        

        $this->contentRule[trim($id)] = [trim($key) => trim($rewrite)];
        $this->updateRule = true;
    }


    /**
     * Delete an rewrite rule
     *
     * @param string $id
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
     * Make compatible whith the component the string to search
     *
     * @param string $search
     */
    private function escape(string $search) : string
    {

        return str_replace("'", "’", $search);
    }



    /**
     * Return an pair key/value
     *
     * @param string $id
     *
     * @return array|null `['search' => 'rewrite']`
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
     * Return all rewrite rules
     *
     * @return array|null
     */
    public function getRules() : ?array
    {
        return $this->contentRule;
    }




    /**
     * Rewrite url
     *
     * @return void
     */
    private function rewrite() : void
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
     * Rebuild rule file
     *
     * @return void
     */
    private function persiste() : void
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
