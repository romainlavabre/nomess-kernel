<?php


namespace Nomess\Event;


interface EventSubscriberInterface
{
    
    public function subscribe( EventListenerInterface $eventListener ): void;
    
    
    public function notified( string $event, $value ): void;
}
