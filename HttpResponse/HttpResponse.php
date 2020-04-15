<?php

namespace NoMess\HttpResponse;


class HttpResponse implements SubjectInterface{

    /**
     * Donnée formatté en json
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
     * Instance de MonitoringData
     *
     * @var MonitoringData
     */
    private $monitoring;

    /**
     * Est injecté d'observeur
     *
     * @param ObserverInterface $obs
     * @param MonitoringData $md
     * @return void
     */
    public function __construct(ObserverInterface $obs,
                                MonitoringData $md)
    {
        $this->observer[] = $obs;
        $this->monitoring = $md;
    }
    
    /**
     * Formatte les donnée renvoyé par l'App
     *
     * @param array $data
     * @return void
     */
    public function render(?array $data) : void
    {
        global $time;
        $time->setXdebug(xdebug_time_index());

        $this->monitoring->database();
        $this->controlStamp($data);

        if(isset($_SESSION['nomess_render'])){
            unset($_SESSION['nomess_render']);
        }

        if(isset($_SESSION['nomess_db'])){
            unset($_SESSION['nomess_db']);
        }

        if(!isset($_SESSION['nomess_attribute'])){
            $_SESSION['nomess_attribute'] = array();
        }

        foreach($_SESSION as $key => $value){
            if($key !== 'nomess_attribute' && $key !== 'private'){
                $_SESSION['nomess_attribute'][$key] = $value;
            }
        }


        $data['attribute'] = $_SESSION['nomess_attribute'];


        $this->jsondata = json_encode($data);
        
        unset($_SESSION['nomess_attribute']);

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