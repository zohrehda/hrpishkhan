<?php

namespace App\Listeners;

use App\Events\RequisitionSent;
use App\Jobs\SendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewEmail
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
     * @param  RequisitionSent  $event
     * @return void
     */
    public function handle(RequisitionSent $event)
    {
        $job=new SendEmail($event->sender,$event->recipient,$event->subject,$event->content)  ;
        dispatch($job) ;
    }
}
