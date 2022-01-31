<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RequisitionSubmitted ;
use App\Jobs\SendEmail;

class SendSubmittedEmail
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
    public function handle(RequisitionSubmitted $event)
    {
        $job=new SendEmail($event->sender,$event->recipient,$event->subject,$event->content)  ;
        dispatch($job) ;
    }
}
