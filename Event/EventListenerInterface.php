<?php


namespace Nomess\Event;


use Nomess\Exception\UnsupportedEventException;

interface EventListenerInterface
{
    
    public const AFTER_CONTAINER_INITIALIZER = 'after_container_initializer';
    public const BEFORE_ROUTE_RESOLVER       = 'before_route_resolver';
    public const AFTER_ROUTE_RESOLVER        = 'after_route_resolver';
    public const BEFORE_FILTER_RESOLVER      = 'before_filter_resolver';
    public const AFTER_FILTER_RESOLVER       = 'after_filter_resolver';
    public const BEFORE_CALL_CONTROLLER      = 'before_call_controller';
    public const AFTER_CALL_CONTROLLER       = 'after_call_controller';
    public const LAUNCH_EXCEPTION            = 'launch_exception';
    
    
    /**
     * Notify subscribe of event
     *
     * @param string $event
     * @param null $value
     * @throws UnsupportedEventException
     */
    public function notify( string $event, $value = NULL ): void;
    
    
    /**
     * Subscribe
     *
     * @param object $instance
     * @param string $event
     * @return bool
     * @throws UnsupportedEventException
     */
    public function follow( object $instance, string $event ): bool;
}
