<?php

namespace NoMess\Service;

use Exception;

class DataCenter
{

    private const DATA_CENTER_FILE              = ROOT . 'App/config/datacenter.php';

    private array $data;


    public function __construct()
    {
        $this->data = require_once self::DATA_CENTER_FILE;
    }

    

    /**
     * Return value associate to the index variable (if not exists, return null)
     *
     * @param string|null $index
     * @return mixed
     */
    public function get(?string $index)
    {
        if(isset($this->data[$index])){
            return $this->data[$index];
        }else{
            return null;
        }
    }
}