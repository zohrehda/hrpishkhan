<?php

namespace App\Listeners;

use App\Events\RequisitionAccepted;
use App\Jobs\SendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAcceptedEmail
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
     * @param  RequisitionAccepted  $event
     * @return void
     */
    public function handle(RequisitionAccepted $event)
    {
        $job=new SendEmail($event->sender,$event->recipient,$event->subject,$event->content)  ;
        dispatch($job) ;
    }
}
