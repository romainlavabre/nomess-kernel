<?php

namespace NoMess\Core;

class Response implements SubjectInterface{

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
     * Est injecté d'observeur
     *
     * @param ObserverInterface $obs
     * @return void
     */
    public function __construct(ObserverInterface $obs)
    {
        $this->observer[] = $obs;
    }
    
    /**
     * Formatte les donnée renvoyé par l'App
     *
     * @param array $data
     * @return void
     */
    public function render(?array $data) : void
    {
        /* dev */global $time;
        /* dev */$time->stopController();
        
        $this->controlStamp($data);
        $this->jsondata = json_encode($data);
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

    private function controlStamp(array $data)
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