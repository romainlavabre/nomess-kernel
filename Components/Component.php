<?php


namespace NoMess\Components;

use NoMess\Exception\WorkException;

abstract class Component
{
    public function __construct()
    {
        $activeComponent = require ROOT . 'App/config/components.php';

        foreach($activeComponent as $key => $value){
            if($key === get_class($this)){

                if($value === false){
                    throw new WorkException('The compenent ' . get_class($this) . ' isn\'t enabled, please, use App/config/components.php for manage your use');
                }
            }
        }
    }
}