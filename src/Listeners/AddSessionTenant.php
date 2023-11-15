<?php

namespace SteelAnts\LaravelTenant\Listeners;

class AddSessionTenant
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        session()->put('tenant_id', $event->user->tenant_id);
    }
}