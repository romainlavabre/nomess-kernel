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
     * Retourne La valeur de $index
     *
     * @param string|null $index
     *
     * @return string
     */
    public function getData(?string $index) : string
    {
        if(isset($this->data[$index])){
            return $this->data[$index];
        }else{
            throw new Exception('DataCenter: La donnée' . $index . ' n\'éxiste pas');
        }
    }
}