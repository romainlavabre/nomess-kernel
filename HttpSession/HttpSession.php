<?php

namespace NoMess\HttpSession;

class HttpSession 
{

    /**
     *
     * @var array
     */
    private $session;


    public function initSession() : void
    {
        if(session_status() === 1){
            session_start();
        }else if(session_status() === 0){
            throw new WorkException('Veuillez activer les sessions pour utiliser noMess');
        }
    }

    public function get($param) 
	{
		return $_SESSION[$param];
	}

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