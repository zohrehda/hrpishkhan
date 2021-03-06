<?php

namespace App\Listeners;

use App\Events\RequisitionAssigned;
use App\Jobs\SendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAssignedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RequisitionAssigned $event)
    {
        $job=new SendEmail($event->sender,$event->recipient,$event->subject,$event->content)  ;
        dispatch($job) ;
    }
}
