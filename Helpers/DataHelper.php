<?php

namespace Nomess\Helpers;

trait DataHelper
{

    private array $data;


    public function __construct()
    {
        if(!isset($this->data)) {
            $this->data = require ROOT . 'config/datacenter.php';
        }
    }

    

    /**
     * Return value associate to the index variable (if not exists, return null)
     *
     * @param string|null $index
     * @return mixed
     */
    public function get(?string $index)
    {
        return (isset($this->data[$index])) ? $this->data[$index] : NULL;
    }
}
