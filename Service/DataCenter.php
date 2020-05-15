<?php

namespace NoMess\Service;

use Exception;

class DataCenter
{

    private const DATA_CENTER_FILE              = ROOT . 'App/config/datacenter.php';

    /**
     *
     * @var array[string][string]
     */
    private $data;

    
    public function __construct()
    {
        $this->data = require_once self::DATA_CENTER_FILE;
    }

    

    /**
     * Retourne La valeur de $index ou null si elle n'existe pas
     *
     * @param string|null $index
     *
     * @return mixed
     */
    public function getData(?string $index)
    {
        if(isset($this->data[$index])){
            return $this->data[$index];
        }else{
            return null;
        }
    }
}