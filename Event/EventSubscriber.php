<?php


namespace Nomess\Event;


class EventSubscriber implements EventSubscriberInterface
{
    
    public function subscribe( EventListenerInterface $eventListener ): void
    {
        // TODO: Implement subscribe() method.
    }
    
    
    public function notified( string $event, $value ): void
    {
        // TODO: Implement notified() method.
    }
}
