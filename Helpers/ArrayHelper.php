<?php


namespace Nomess\Helpers;


trait ArrayHelper
{
    public function isEmptyArray(?array $array): bool
    {
        return !is_null($array) ? empty($array) : FALSE;
    }
    
    public function countArray(?array $array): bool
    {
        return !is_null($array) ? count($array) : 0;
    }
    
    public function arrayContainsValue($value, ?array $array): bool
    {
        return !is_null($array) ? in_array($value, $array) : FALSE;
    }
    
    public function arrayContainsKey($key, ?array $array): bool
    {
        return !is_null($array) ? array_key_exists($key, $array) : FALSE;
    }
    
    public function keysArray(?array $array): array
    {
        return !is_null($array) ? array_keys($array) : [];
    }
    
    public function valuesArray(?array $array): array
    {
        return !is_null($array) ? array_values($array) : [];
    }
    
    public function indexOf($value, ?array $array)
    {
        return !is_null($array) ? array_search($value, $array) : NULL;
    }
    
    public function prepareArray(?array $array): array
    {
        return !is_null($array) ? $array : [];
    }
}
