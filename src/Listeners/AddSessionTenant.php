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
        switch (config('tenant.resolver', 'subdomain')) {
            case 'subdomain':
            case 'path':
                #session()->put('tenant_id', $event->user->getCurrentTenant()->id);
                break;

            case 'session':
                break;

            default:
                break;
        }
    }
}
