<?php

namespace App\Listeners;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class EmailChangedListener
{
    /**
     * Handle user login events.
     *
     * @param $event
     */
    public function process($event)
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->save();
            $event->user->sendEmailVerificationNotification();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\Listeners\EmailChangedListener@process'
        );

        $events->listen(
            'App\Events\ChangedEmail',
            'App\Listeners\EmailChangedListener@process'
        );
    }
}
