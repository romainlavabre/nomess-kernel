<?php

namespace NoMess\HttpSession;

use NoMess\Exception\WorkException;

class HttpSession 
{


    /**
     * Initialise la session
     *
     * @return void
     */
    public function initSession() : void
    {
        if(session_status() === 1){
            session_start();

        }else if(session_status() === 0){
            throw new WorkException('Veuillez activer les sessions pour utiliser noMess');
        }
    }


    /**
     * Recupère une valeur du tableau de session
     *
     * @param mixed $param
     *
     * @return mixed
     */
    public function get($param) 
	{
		return $_SESSION[$param];
	}

    /**
     * Ajoute une valeur dans la session
     *
     * @param mixed $key
     * @param mixed $value
     * @param bool $reset Supprime la clé $key avant toute insertion
     *
     * @return void
     */
	public function set($key, $value, $reset = false) : void
	{
		if($reset === true){
			unset($_SESSION[$key]);
		}

        if(\is_array($value)){

            foreach($value as $keyArray => $valArray){

                $_SESSION[$key][$keyArray] = $valArray;
            }

        }else{
            $_SESSION[$key] = $value;
        }
	}
}