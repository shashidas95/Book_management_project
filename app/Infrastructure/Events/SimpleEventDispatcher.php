<?php

namespace App\Infrastructure\Events;

class SimpleEventDispatcher
{
    private array $listeners = [];
    public function listener(string $eventClass, callable $listener)
    {
        $this->listeners[$eventClass][] = $listener;
    }
    public function dispatch(object $event)
    {
        // 1. Trigger your custom/manual listeners
        $eventClass = get_class($event);
        if (isset($this->listeners[$eventClass])) {
            foreach ($this->listeners[$eventClass] as $listener) {
                $listener($event);
            }
        }

        // 2. TRIGGER LARAVEL NATIVE EVENT SYSTEM
        // This ensures that listeners registered in AppServiceProvider
        // and Queued listeners (ShouldQueue) are executed.
        event($event);
    }
}
