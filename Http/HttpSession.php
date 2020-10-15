<?php

namespace Nomess\Http;

use Nomess\Exception\InvalidParamException;
use Nomess\Exception\NomessException;

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
    
    
    public function __construct()
    {
        if( session_status() === 1 ) {
            
            session_start();
            if( !isset( $_SESSION[self::ID_MODULE_SECURITY] ) ) {
                $_SESSION[self::ID_MODULE_SECURITY] = array();
            }
            
            $this->securityInitialized();
            
            if( isset( $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] ) ) {
                $this->executedModule();
            }
        } elseif( session_status() === 0 ) {
            throw new NomessException( 'Please active the session' );
        }
    }
    
    
    /**
     * @param $index
     * @return bool
     */
    public function has( $index ): bool
    {
        return isset( $_SESSION[$index] );
    }
    
    
    /**
     * Return reference of the entry associate to index
     * Null if doesn't exists
     *
     * @param mixed $index
     * @return mixed|void
     */
    public function &getReference( $index )
    {
        if( isset( $_SESSION[$index] ) ) {
            return $_SESSION[$index];
        }
    }
    
    
    /**
     * Return the value assiciate to index, Null if doesn't exists
     *
     * @param mixed $index
     * @return mixed
     */
    public function get( $index )
    {
        return $_SESSION[$index] ?? NULL;
    }
    
    
    /**
     * Delete the entry associate to index variable
     *
     * @param string $index
     * @return $this
     */
    public function delete( string $index ): self
    {
        if( array_key_exists( $index, $_SESSION ) ) {
            unset( $_SESSION[$index] );
        }
        
        return $this;
    }
    
    
    /**
     * Add value
     *
     * @param mixed $key
     * @param mixed $value
     * @param bool $reset Delete value associate to the key before insertion
     * @return $this
     */
    public function set( $key, $value, $reset = FALSE ): self
    {
        if( $reset ) {
            unset( $_SESSION[$key] );
        }
        
        if( \is_array( $value ) ) {
            
            if( !array_key_exists( $key, $_SESSION ) || !is_array( $_SESSION[$key] ) ) {
                $_SESSION[$key] = array();
            }
            
            foreach( $value as $keyArray => $valArray ) {
                
                $_SESSION[$key][$keyArray] = $valArray;
            }
        } else {
            $_SESSION[$key] = $value;
        }
        
        return $this;
    }
    
    
    /**
     * Modify the lifetime of cookie session
     *
     * @param int $time   Time in second
     * @param bool $force Update the cookie even id the value of lifetime is equals to time variable
     * @return $this
     */
    public function setLifeTime( int $time, bool $force = FALSE ): self
    {
        
        if( !array_key_exists( self::SESSION_SET_LIFE_TIME, $_SESSION[self::ID_MODULE_SECURITY] ) || $force === TRUE ) {
            $content = $_SESSION;
            
            session_destroy();
            ini_set( 'session.gc_maxlifetime', $time );
            session_set_cookie_params( $time );
            session_start();
            
            $_SESSION = $content;
            
            $_SESSION[self::ID_MODULE_SECURITY][self::SESSION_SET_LIFE_TIME] = TRUE;
        }
        
        return $this;
    }
    
    
    /**
     * @return $this
     */
    public function kill(): self
    {
        $toolbar = $_SESSION['app']['toolbar'] ?? NULL;
        
        $_SESSION = [];
        $params   = session_get_cookie_params();
        setcookie( session_name(), '', time() - 42000,
                   $params["path"], $params["domain"],
                   $params["secure"], $params["httponly"]
        );
        
        
        session_destroy();
        
        if( !is_null( $toolbar ) ) {
            $_SESSION['app']['toolbar'] = $toolbar;
        }
        
        return $this;
    }
    
    
    
    /*
     * Configuration des brique de sécurité
     */
    
    
    /**
     * Installation of security module, valid for lifetime of session
     *
     * @param bool $userAgentSystem        if TRUE, the useragent will be controlled
     * @param bool $ticketSystem           If TRUE, an ticket system will be initialize
     * @param bool $ipSystem               If TRUE, the IP ADRESS will be controlled
     * @param bool $bindTicketIp           If TRUE, add an felexibility for IP modules, if IP doesn't match but the
     *                                     ticket is valid, the connexion will be accepted
     * @param array[bool $userAgentSystem, bool $ipSystem]|null $recoveryConfig Array of secondary configuration in
     *                                     case of error from ticket modules (Client doesn't accept the cookie)
     * @return $this
     * @throws InvalidParamException
     */
    public function installSecurityModules( bool $userAgentSystem, bool $ticketSystem, bool $ipSystem, bool $bindTicketIp = FALSE, ?array $recoveryConfig = NULL ): self
    {
        
        if( $ticketSystem === TRUE ) {
            setcookie( 'dpr__', 'sdf846dsf68fs3k4f4rs53f8sddfre', time() + 60 * 20, '/' );
        }
        
        
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET]     = $ticketSystem;
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] = ( $userAgentSystem === TRUE ) ? $_SERVER['HTTP_USER_AGENT'] : FALSE;
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_IP]         = ( $ipSystem === TRUE ) ? $_SERVER['REMOTE_ADDR'] : NULL;
        $_SESSION[self::ID_MODULE_SECURITY][self::BIND_TICKET_IP]    = $bindTicketIp;
        $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG]   = $recoveryConfig;
        
        if( $recoveryConfig !== NULL && count( $recoveryConfig ) !== 2 ) {
            throw new InvalidParamException( 'RecoveryConfig must contain exactly 2 parameters: $userAgentSystem and $ipSystem| $ticketSystem and $bindTicketIp will be initialized to false' );
        }
        
        return $this;
    }
    
    
    /*
     * Executor
     */
    
    
    /**
     * Execute the modules
     */
    private function executedModule(): void
    {
        $success = TRUE;
        
        if( $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] !== FALSE ) {
            $success = $this->securityUserAgent();
        }
        
        if( $success === TRUE ) {
            if( isset( $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] ) && $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] === TRUE ) {
                $success = $this->securityTicket();
            }
        }
        
        if( ( $success === TRUE )
            && $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_IP] !== FALSE ) {
            
            $success = $this->securityIpUser();
            
            if( $success === TRUE && $_SESSION[self::ID_MODULE_SECURITY][self::BIND_TICKET_IP] === TRUE ) {
                $success = TRUE;
            }
        }
        
        
        //r($success);
        if( $success === FALSE ) {
            session_regenerate_id( TRUE );
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
    private function securityInitialized(): void
    {
        if( !isset( $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE] ) ) {
            session_regenerate_id( TRUE );
            $_SESSION[self::ID_MODULE_SECURITY]                          = array();
            $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE] = 1;
        }
        
        if( !isset( $_SESSION['app']['_token'] ) ) {
            $_SESSION['app']['_token'] = md5( uniqid( '_token::', TRUE ) . str_shuffle( 'ABCDEFGHIJ' ) );
        }
    }
    
    
    /**
     * Module useragent
     *
     * @return bool
     */
    private function securityUserAgent(): bool
    {
        return $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_USER_AGENT] === $_SERVER['HTTP_USER_AGENT'];
    }
    
    
    /**
     * Module ticket
     *
     * @return bool
     * @throws InvalidParamException
     */
    private function securityTicket(): bool
    {
        
        if( $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE] === 1 ) {
            $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_INITIALIZE]++;
            
            if( !isset( $_COOKIE['dpr__'] ) ) {
                $this->installSecurityModules(
                    $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG][0],
                    FALSE,
                    $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG][1]
                );
            }
            
            $this->getTicket();
            
            unset( $_SESSION[self::ID_MODULE_SECURITY][self::RECOVERY_CONFIG] );
            
            return TRUE;
        }
        
        if( isset( $_COOKIE[self::MODULE_TICKET] ) && isset( $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] ) ) {
            
            if( $_COOKIE[self::MODULE_TICKET] === $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] ) {
                
                $this->getTicket();
                
                return TRUE;
            }
            
            return FALSE;
        }
        
        session_regenerate_id( TRUE );
        
        $this->getTicket();
        
        return FALSE;
    }
    
    
    /**
     * Create an ticket on demand
     */
    private function getTicket(): void
    {
        $ticket = session_id() . microtime() . random_int( 0, 9999999999 );
        $ticket = md5( $ticket );
        
        setcookie( self::MODULE_TICKET, $ticket, time() + ( 60 * 20 ), '/' );
        $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_TICKET] = $ticket;
    }
    
    
    /**
     * Module IpUser
     *
     * @return bool
     */
    private function securityIpUser(): bool
    {
        return $_SESSION[self::ID_MODULE_SECURITY][self::MODULE_IP] === $_SERVER['REMOTE_ADDR'];
    }
}
