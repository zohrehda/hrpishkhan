<?php

namespace App\Listeners;

use App\Events\RequisitionChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SendRequisitionChangedNotification
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
     * @param RequisitionChanged $event
     * @return void
     */
    public function handle(RequisitionChanged $event)
    {
        $requisition = $event->requisition;
        $users = $requisition->determiners->merge($requisition->viewers)->merge($requisition->user_assigned)->push($requisition->owner)->where('id','!=',Auth::user()->id);
        Notification::send($users, new \App\Notifications\RequisitionChanged($requisition,$event->status));
    }
}
