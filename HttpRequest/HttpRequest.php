<?php

namespace NoMess\HttpRequest;

use DI\ContainerBuilder;
use DI\Container;

class HttpRequest 
{

    /**
     *
     * @var HttpSession
     */
    private $session;


    public function __construct(HttpSession $session)
    {
        $this->session = $session;
    }

    public function setParameter(string $key, $value) : void
	{
        $this->session->set('nomess_attribute', [$key => $value]);
	}

    /**
     * Retourne les paramètre $_POST et $_GET, si concurrence, renvoie la valeur de $_POST
     *
     * @param string $index
     * @return string
     */
    public function getParameter(string $index) : ?string
    {
        if(isset($_POST[$index])){

            return $_POST[$index];

        }else if(isset($_GET[$index])){

            return $_GET[$index];

        }else{

            return null;
        }
    }
}