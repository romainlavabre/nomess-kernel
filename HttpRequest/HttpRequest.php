<?php

namespace NoMess\HttpRequest;

use NoMess\Exception\WorkException;
use NoMess\HttpSession\HttpSession;

class HttpRequest 
{


    private const ERROR             = 'error';
    private const SUCCESS           = 'success';
    private const PARAMETERS        = 'nomess_attribute';


    /**
     *
     * @var HttpSession
     */
    private $session;


    /** 
     *
     * @param HttpSession $session
     */
    public function __construct(HttpSession $session)
    {
        $this->session = $session;
    }


    /**
     * Ajoute une erreur
     *
     * @param string $message
     * @return void
     */
    public function setError(string $message) : void
    {
        $_SESSION[self::ERROR][] = $message;
    }

    /**
     * Ajoute un succès
     *
     * @param string $message
     * @return void
     */
    public function setSuccess(string $message) : void
    {
        $_SESSION[self::SUCCESS][] = $message;
    }

    /**
     * Ajoute un paramêtre à la requête
     *
     * @param mixed $key
     * @param [type] $value
     * @return void
     */
    public function setParameter($key, $value) : void
	{
        $this->session->set(self::PARAMETERS, [$key => $value]);
	}

    /**
     * Retourne les paramètre $_POST et $_GET, si concurrence, renvoie la valeur de $_POST
     *
     * @param string $index
     * @return mixed
     */
    public function getParameter(string $index)
    {
        if(isset($_POST[$index])){

            return $_POST[$index];

        }else if(isset($_GET[$index])){

            return $_GET[$index];

        }else{

            return null;
        }
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
		$_SESSION['nomess_render'][$serviceStamp] = $value;
	}


    /**
     * Récupère une valeur temporaire
     *
     * @param string $serviceStamp
     * @return mixed
     */
	public function getRender(string $serviceStamp) 
	{
		if(isset($_SESSION['nomess_render'][$serviceStamp])){
			return $_SESSION['nomess_render'][$serviceStamp];
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
}
