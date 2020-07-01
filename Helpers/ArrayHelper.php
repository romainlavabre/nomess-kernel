<?php


namespace Nomess\Helpers;


trait ArrayHelper
{
    /**
     * Return the value associate to key, if key doesn't exists or value is null, return null
     *
     * @param mixed $key
     * @param array|null $array
     * @return mixed
     */
    public function arrayByKey(string $key, ?array $array)
    {
        if(isset($array[$key])){
            return $array[$key];
        }else{
            return null;
        }

        return null;
    }



    /**
     * Recherche une valeur par valeur dans le tableau cible $tab
     * Search an entry by value inside array, return value or null
     *
     * @param mixed $value The value to search
     * @param array|null $tab
     * @param string $method If is an array of object, specify the accessor
     * @return mixed
     */
    public function arrayByValue(string $value, ?array $tab, ?string $method = null)
    {
        if($tab !== null){
            foreach($tab as $value2){

                if($method !== null){
                    if(trim($value2->$method()) === trim($value)){
                        return $value2;
                    }
                }else{
                    if($value === $value2){
                        return $value2;
                    }
                }
            }
        }

        return null;
    }
}
