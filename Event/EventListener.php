<?php


namespace Nomess\Event;


use Nomess\Exception\UnsupportedEventException;

class EventListener implements EventListenerInterface
{
    
    private array $subscriber = [];
    
    
    /**
     * @param EventSubscriberInterface[] $eventSubscriber
     */
    public function __construct( array $eventSubscriber )
    {
    }
    
    
    /**
     * @inheritDoc
     */
    public function notify( string $event, $value = NULL ): void
    {
        if( $this->isSupportedEvent( $event ) ) {
            if( $this->hasSubscriber( $event ) ) {
                /** @var EventSubscriberInterface $instance */
                foreach( $this->subscriber[$event] as $instance ) {
                    $instance->notified( $event, $value );
                }
            }
            
            return;
        }
        
        
        throw new UnsupportedEventException( 'The event ' . $event . ' is not supported' );
    }
    
    
    /**
     * @inheritDoc
     */
    public function follow( object $instance, string $event ): bool
    {
        if( !$this->isSupportedEvent( $event ) ) {
            throw new UnsupportedEventException( 'The event ' . $event . ' is not supported' );
        }
        
        if( !array_key_exists( $event, $this->subscriber ) ) {
            $this->subscriber[$event] = [];
        }
        
        $this->subscriber[$event][] = $instance;
        
        return TRUE;
    }
    
    
    private function isSupportedEvent( string $event ): bool
    {
        $events = [
            EventListenerInterface::AFTER_CONTAINER_INITIALIZER,
            EventListenerInterface::BEFORE_ROUTE_RESOLVER,
            EventListenerInterface::AFTER_ROUTE_RESOLVER,
            EventListenerInterface::BEFORE_FILTER_RESOLVER,
            EventListenerInterface::AFTER_FILTER_RESOLVER,
            EventListenerInterface::BEFORE_CALL_CONTROLLER,
            EventListenerInterface::AFTER_CALL_CONTROLLER,
            EventListenerInterface::LAUNCH_EXCEPTION
        ];
        
        return in_array( $event, $events, TRUE );
    }
    
    
    private function hasSubscriber( string $event ): bool
    {
        return array_key_exists( $event, $this->subscriber );
    }
}
