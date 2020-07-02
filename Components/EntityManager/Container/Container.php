<?php


namespace NoMess\Components\EntityManager\Container;


class Container
{
   private array $storage = array();

   public function get(string $classname, int $id): ?object
   {
       if(isset($this->storage[$classname][$id])){
           return $this->storage[$classname][$id];
       }

       return NULL;
   }

   public function set(string $classname, object $object): void
   {
       $this->storage[$classname][$object->getId()] = $object;
   }
}
