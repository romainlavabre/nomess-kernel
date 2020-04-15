<?php


class Control{

    const TYPE = array(
        'bool',
        'int',
        'float',
        'double',
        'string',
        'array',
        'object',
        'callable',
        'iterable',
        'resource',
        'null'

    );

    /**
     * Controle la validité du typage
     *
     * @param [type] $type
     * @return boolean
     */
    public static function controlType($type) : bool
    {
        foreach(Control::TYPE as $value){
            if(strtolower($type) === $value){
                return true;
            }
        }

        return false;
    }
}