<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRequisition extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $user_name ;
     public $msg ;
    public function __construct($user_name,$msg)
    {
        //
        $this->user_name=$user_name ;
         $this->msg=$msg ;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user_name=$this->user_name ;
        $msg=$this->msg ;
        return $this->view('mail.new_requisition',compact('user_name','msg'));
    }
}
