<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequisitionRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender;
    public $recipient;
    public $subject;
    public $content;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender, $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->subject = 'Rejected Requisition';
        $this->content = 'Hello Dear ' . $this->recipient->name . "<br>" . 'your Requisition has been Rejected.' . "<br>" . "<a href='" . config('app.url') . "' target='_blank'> click here to redirect to HR-pishkhan panel </a>";

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
