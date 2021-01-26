<?php

namespace App\Listeners;

use App\Events\RequisitionRejected;
use App\Jobs\SendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRejectedEmail
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
     * @param  RequisitionRejected  $event
     * @return void
     */
    public function handle(RequisitionRejected $event)
    {
        $job=new SendEmail($event->sender,$event->recipient,$event->subject,$event->content)  ;
        dispatch($job) ;
    }
}
