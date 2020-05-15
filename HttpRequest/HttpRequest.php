<?php

namespace NoMess\HttpRequest;

use NoMess\Exception\WorkException;
use NoMess\HttpSession\HttpSession;

class HttpRequest 
{


    private const SESSION_DATA      = 'nomess_persiste_data';



    /**
     *
     * @var array
     */
    private $error = array();


    /**
     *
     * @var array
     */
    private $success = array();


    /**
     *
     * @var array
     */
    private $parameters = array();


    /**
     *
     * @var array
     */
    private $render = array();



    
    public function __construct()
    {


        if(isset($_SESSION[self::SESSION_DATA])){
            foreach($_SESSION[self::SESSION_DATA] as $key => $data){
                if($key === 'error'){
                    $this->error = $data;
                }else if($key === 'success'){
                    $this->success = $data;
                }else{
                    $this->parameters[$key] = $data;
                }
            }

            unset($_SESSION[self::SESSION_DATA]);
        }
    }


    /**
     * Ajoute une erreur
     *
     * @param string $message
     * @return void
     */
    public function setError(string $message) : void
    {
        $this->error[] = $message;
    }



    /**
     * Ajoute un succès
     *
     * @param string $message
     * @return void
     */
    public function setSuccess(string $message) : void
    {
        $this->success[] = $message;
    }



    /**
     * Supprime les message de succès
     *
     * @return void
     */
    public function resetSuccess() : void
    {
        $this->success = null;
    }




    /**
     * Ajoute un paramêtre à la requête
     * Si value est un tableau, la clé sera associé à l'élément parent:
     * 
     * $param['key']['keyOfValue] = value;
     * et non 
     * $param['key'] = ['keyOfValue => value];
     * 
     * 
     * 
     * @param mixed $key
     * @param mixed $value 
     * @return void
     */
    public function setParameter($key, $value) : void
	{
        $this->parameters[$key] = $value;
	}

    /**
     * Retourne les paramètre $_POST et $_GET, si concurrence, renvoie la valeur de $_POST
     * Si le paramêtre n'éxiste pas ou est vide, NULL est retourné
     *
     * @param string $index
     * @param bool $escape si TRUE (par defaut) la fonction htmlspecialchars sera appliqué
     *
     * @return mixed
     */
    public function getParameter(string $index, bool $escape = true)
    {

        if(isset($_POST[$index]) && !empty($_POST[$index])){

            if($escape === true){
                if(is_array($_POST[$index])){
                    array_walk_recursive($_POST[$index], function($key, &$value){
                        $value = htmlspecialchars($value);
                    });
                }

                return $_POST[$index];
            }else{
                return $_POST[$index];
            }

        }else if(isset($_GET[$index]) && !empty($_GET[$index])){

            if($escape === true){

                if(is_array($_GET[$index])){
                    array_walk_recursive($_GET[$index], function($key, &$value){
                        $value = htmlspecialchars($value);
                    });
                }

                return $_GET[$index];
            }else{
                return $_GET[$index];
            }

        }else{

            return null;
        }
    }


    /**
     * Déconseillé
     * Retourne la variable POST complète
     *
     * @return array|null
     */
    public function getParameters() : ?array
    {
        return ['POST' => $_POST, 'GET' => $_GET];
    }

    
    /**
     * Rends temporairement une valeur
     *
     * @param string $serviceStamp
     * @param mixed $value
     * @return void
     */
    public function setRender(string $serviceStamp, $value) : void
	{
		$this->render[$serviceStamp] = $value;
	}


    /**
     * Récupère une valeur temporaire
     *
     * @param string $serviceStamp
     * @return mixed
     */
	public function getRender(string $serviceStamp) 
	{
		if(array_key_exists($serviceStamp, $this->render)){
            return $this->render[$serviceStamp];
		}

		throw new WorkException($serviceStamp . ' n\'a rien retourné');
	}


    /**
     * Retourne les fichiers envoyé par POST (Global $_FILES)
     *
     * @param string $index
     *
     * @return array|null
     */
    public function getFile(string $index) : ?array
    {
        if(isset($_FILES[$index])){
            return $_FILES[$index];
        }

        return null;
    }




    /**
     * Retour le cookie associé a l'index, NULL si il est vide ou n'éxiste pas
     *
     * @param string $index
     *
     * @return mixed
     */
    public function getCookie(string $index)
    {
        if(isset($_COOKIE[$index]) && !empty($_COOKIE[$index])){
            return $_COOKIE[$index];
        }else{
            return null;
        }
    }


    /**
     * Retourne toute les donnée de $_POST, si $escape vaut true, la fonction 
     * htmlspecialchars sera appliqué a toute les valeurs recursivement
     *
     * @param bool $escape
     *
     * @return array|null
     */
    public function getPost(bool $escape = false) : ?array
    {
        if($escape === true){
            array_walk_recursive($_POST, function($key, &$value){
                htmlspecialchars($value);
            });

        }

        return $_POST;
    }

    /**
     * Retourne toute les donnée de $_GET, si $escape vaut true, la fonction 
     * htmlspecialchars sera appliqué à toute les valeurs recursivement
     *
     * @param bool $escape
     *
     * @return array|null
     */
    public function getGet(bool $escape = false) : ?array
    {
        if($escape === true){
            array_walk_recursive($_GET, function($key, &$value){
                htmlspecialchars($value);
            });
        }

        return $_GET;
    }


    /**
     * Retourne le contenu de $_SERVER
     *
     * @return array
     */
    public function getServer() : array
    {
        return $_SERVER;
    }


    public function getData() : array
    {
        $array = array();

        if(!empty($this->error)){
            $array['error'] = $this->error;
        }

        if(!empty($this->success)){
            $array['success'] = $this->success;
        }

        if(!empty($this->parameters)){
            $array = array_merge($array, $this->parameters);
        }
        
        return $array;
    }
}
