<?php

namespace NoMess\HttpResponse;

use NoMess\Web\ObserverInterface;
use NoMess\DataManager\DataManager;
use NoMess\Exception\WorkException;


class HttpResponse implements SubjectInterface{


    /**
     * Session
     */
    private const SESSION_RENDER                = 'nomess_render';
    private const SESSION_DATABASE              = 'nomess_db';
    private const SESSION_PARAMETERS            = 'nomess_attribute';



    /**
     * Données formattées en json
     *
     * @var string
     */
    private $jsondata;

    /**
     * Contient les observateurs
     *
     * @var array
     */
    private $observer = array();

    /**
     * Instance de DataManager
     *
     * @var DataManager
     */
    private $monitoring;

    /**
     * 
     * Est injecté d'observeur
     *
     * @param ObserverInterface $obs
     * @param DataManager $md
     * @return void
     */
    public function __construct(ObserverInterface $obs,
                                DataManager $md)
    {
        $this->observer[] = $obs;
        $this->monitoring = $md;
    }
    
    /**
     * Formatte les données renvoyé par l'App
     *
     * @param array $data
     * @return void
     */
    public function render(?array $data) : void
    {

        $this->monitoring->database();
        $this->controlStamp($data);

        if(isset($_SESSION[self::SESSION_RENDER])){
            unset($_SESSION[self::SESSION_RENDER]);
        }

        if(isset($_SESSION[self::SESSION_DATABASE])){
            unset($_SESSION[self::SESSION_DATABASE]);
        }

        if(!isset($_SESSION[self::SESSION_PARAMETERS])){
            $_SESSION[self::SESSION_PARAMETERS] = array();
        }

        foreach($_SESSION as $key => $value){
            if($key !== self::SESSION_PARAMETERS && $key !== 'private'){
                $_SESSION[self::SESSION_PARAMETERS][$key] = $value;
            }
        }


        $data['attribute'] = $_SESSION[self::SESSION_PARAMETERS];


        $this->jsondata = json_encode($data);
        
        unset($_SESSION[self::SESSION_PARAMETERS]);

        $this->notify();
    }

    /**
     * Retourne des données formattés
     *
     * @return string
     */
    public function collectData() : string
    {
        return $this->jsondata;
    }

    /**
     * Notifie les observeur d'une modification
     *
     * @return void
     */
    public function notify() : void
    {
        foreach($this->observer as $observer){
            $observer->alert($this);
        }
    }

    public function controlStamp(array $data) : void
    {
        $find = false;

        foreach($data as $key => $value){
            if($key === 'stamp'){
                $find = true;
            }
        }

        if(!$find){
            throw new WorkException("Votre reponse doit contenir une signature: 'stamp' => 'Controller:method'");
        }
    }
}