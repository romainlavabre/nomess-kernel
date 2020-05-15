<?php


namespace NoMess\Component;

use NoMess\Exception\WorkException;

abstract class Component
{
    public function __construct()
    {
        $activeComponent = require ROOT . 'App/config/component.php';

        foreach($activeComponent as $key => $value){
            if($key === get_class($this)){

                if($value === false){
                    throw new WorkException('Le composant ' . get_class($this) . ' n\'est pas activé, et ne peut pas etre utilisé. Utilisez App/config/composant.php pour gérer vos composants');
                }
            }
        }
    }
}