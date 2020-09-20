<?php


namespace Nomess\Event;


interface EventSubscriberInterface
{
    
    public function subscribe(): void;
    
    
    public function notified( string $event, $value ): void;
}
