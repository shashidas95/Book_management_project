<?php

use App\Infrastructure\Events\EventDispatcherInterface;
class LaravelEventDispatcher implements EventDispatcherInterface{
    public function dispatch(object $event): void
    {
        // We simply hand the event over to Laravel's native system.
        // This automatically triggers any Listeners (Queued or Sync)
        // registered in your AppServiceProvider.
        event($event);
    }
}
