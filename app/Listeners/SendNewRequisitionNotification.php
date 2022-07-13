<?php

namespace App\Listeners;

use App\Events\RequisitionCreated;
use App\Notifications\NewRequisition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewRequisitionNotification
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
     * @param RequisitionCreated $event
     * @return void
     */
    public function handle(RequisitionCreated $event)
    {
        $requisition = $event->requisition;
        $users = $event->users;
        $users->notify(new NewRequisition($requisition));

        //  $requisition->current_determiner()->notify(new NewRequisition($requisition));
    }
}
