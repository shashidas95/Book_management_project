<?php
namespace App\Infrastructure\Events;
interface EventDispatcherInterface{
    /**
     * Dispatch an event to its listeners.
     */
    public function dispatch(object $event): void;
}
