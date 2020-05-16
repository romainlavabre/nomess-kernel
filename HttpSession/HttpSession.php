<?php

namespace NoMess\HttpSession;

use Exception;
use NoMess\Exception\WorkException;

class HttpSession 
{

    private const ID_MODULE_SECURITY    = 'nomess_session_security';
    private const MODULE_INITIALIZE     = 'initialized';
    private const MODULE_USER_AGENT     = 'user_agent';
    private const MODULE_TICKET         = 'jdf_rt';
    private const MODULE_IP             = 'ip';
    private const BIND_TICKET_IP        = 'bind_ticket_ip';
    private const RECOVERY_CONFIG       = 'recovery_config';
    private const SESSION_SET_LIFE_TIME = 'session_set_life_time';



    /**
     * Initialise la session
     *
     * @return void
     */
    public function initSession() : void
    {

        if(session_status() === 1){

            session_start();
            
            if(!isset($_SESSION[self::ID_MODULE_SECURITY])){
                $_SESSION[self::ID_MODULE_SECURITY] = array();
            }

            $this->securityInitialized();

            if(isset($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT])){
                $this->executedModule();
            }

            

        }else if(session_status() === 0){
            throw new WorkException('Veuillez activer les sessions pour utiliser noMess');
        }
    }


    /**
     * Retourne la valeur associé à l'index par référence
     * Null si elle n'éxiste pas
     *
     * @param mixed $index
     *
     * @return void
     */
    public function &getReference($index)
    {
        if(isset($_SESSION[$index])){
            return $_SESSION[$index];
        }else{
            return null;
        }
    }

    /**
     * Retourne la valeur associé a l'index
     * Null si elle n'éxiste pas
     *
     * @param mixed $index
     *
     * @return mixed
     */
    public function get($index) 
	{
        if(isset($_SESSION[$index])){
            return $_SESSION[$index];
        }else{
            return null;
        }
    }
    
    public function delete(string $index){
        if(array_key_exists($index, $_SESSION)){
            unset($_SESSION[$index]);
        }
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


    /**
     * Modifie la valeur de lifetime du cookie et de la session
     *
     * @param int $time Temps en seconde
     * @param bool $force Mets à jour le cookie même si la valeur lifetime est egale à $time
     *
     * @return void
     */
    public function setLifeTime(int $time, bool $force = false) : void
    {

        if(!array_key_exists(self::SESSION_SET_LIFE_TIME, $_SESSION[self::ID_MODULE_SECURITY]) || $force === true){
            $content = $_SESSION;

            session_destroy();
            ini_set('session.gc_maxlifetime', $time);
            session_set_cookie_params($time);
            session_start();

            $_SESSION = $content;

            $_SESSION[self::ID_MODULE_SECURITY][self::SESSION_SET_LIFE_TIME] = true;

        }
    }



    /*
     * Configuration des brique de sécurité
     */ 



    /**
     * Installation des modules de sécutité, la configuration est valable pour la duréé de vie de la session
     *
     * @param bool $userAgentSystem Si TRUE le userAgent sera pris en compte pour la validation
     * @param bool $ticketSystem Si TRUE un systeme de ticket sera initialisé
     * @param bool $ipSystem Si TRUE l'ip sera pris en compte pour la validation
     * @param bool $bindTicketIp Si TRUE apporte une souplesse pour le modules IP: 
     * Si l'ip ne correspond pas et que les tickets sont valides, la validation passera le controle
     * @param array[bool $userAgentSystem, bool $ipSystem]|null $recoveryConfig Tableau de configuration secondaire en cas d'échec du module de ticket 
     * (Le client n'accepte pas les cookies)
     *
     * @return int
     * @throws Exception
     * 
     */
    public function installSecurityModules(bool $userAgentSystem, bool $ticketSystem, bool $ipSystem, bool $bindTicketIp = false, ?array $recoveryConfig = null) : void
    {

        if($ticketSystem === true){
            setcookie('dpr__', 'sdf846dsf68fs3k4f4rs53f8sddfre', time() + 60 * 20, '/');
        }


        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] = $ticketSystem;
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] = ($userAgentSystem === true) ? $_SERVER['HTTP_USER_AGENT'] : false;
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_IP] = ($ipSystem === true) ? $_SERVER['REMOTE_ADDR'] : null;
        $_SESSION[self::ID_MODULE_SECURITY][self::BIND_TICKET_IP] = $bindTicketIp;
        $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG] = $recoveryConfig;

        if($recoveryConfig !== null && count($recoveryConfig) !== 2){
            throw new Exception('RecoveryConfig doit contenir exactement 2 paramêtres: $userAgentSystem et $ipSystem| $ticketSystem et $bindTicketIp seront initialisé à false');
        }
    }


    /*
     * Executor
     */


    /**
     * Execute les modules
     *
     * @return void
     */
    private function executedModule() : void
    {
        $success = true;

        if($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] !== false){
            $success = $this->securityUserAgent();
        }

        if($success === true){
            if(isset($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET]) && $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] === true){
                $success = $this->securityTicket();
            }
        }

        if($success === true){
            if($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_IP] !== false){
                $success = $this->securityIpUser();
                if($success === true && $_SESSION[self::ID_MODULE_SECURITY][self::BIND_TICKET_IP] === true){
                    $success = true;
                }
            }
        }


        //r($success);
        if($success === false){
            session_regenerate_id(true);
            $_SESSION = array();
        }
    }



    /*
     *  Module de sécurité de session
     */


    /**
     * Module Initialized
     *
     * @return void
     */
    private function securityInitialized() : void
    {
        if(!isset($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE])){
            session_regenerate_id(true);
            $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE] = 1;
        }
    }



    /**
     * Module user agent
     *
     * @return bool
     */
    private function securityUserAgent() : bool
    {
        if($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] === $_SERVER['HTTP_USER_AGENT']){
            return true;
        }else{

            return false;
        }

    }


    /**
     * Module ticket
     *
     * @return void
     */
    private function securityTicket() : bool
    {

        if($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE] === 1){
            $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE]++;

            if(!isset($_COOKIE['dpr__'])){
                $this->installSecurityModules(
                    $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG][0], 
                    false, 
                    $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG][1]
                );
            }

            $this->getTicket();
            
            unset($_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG]);

            return true;
        }else if(isset($_COOKIE[self::MODULE_TICKET]) && isset($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET])){

            if($_COOKIE[self::MODULE_TICKET] === $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET]){

                $this->getTicket();

                return true;
            }else{
                return false;
            }
        }else{
            session_regenerate_id(true);

            $this->getTicket();
            return false;
        }
    }


    /**
     * Créer un ticket sur demande
     *
     * @return void
     */
    private function getTicket() : void
    {
        $ticket = session_id().microtime().rand(0,9999999999);
        $ticket = md5($ticket);

        setcookie(self::MODULE_TICKET, $ticket, time() + (60 * 20), '/');
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] = $ticket;
    }



    /**
     * Module IpUser
     *
     * @return bool
     */
    private function securityIpUser() : bool
    {
        if($_SESSION[self::ID_MODULE_SECURITY][self::MODULE_IP] === $_SERVER['REMOTE_ADDR']){
            return true;
        }else{
            return false;
        }

        return true;
    }
}