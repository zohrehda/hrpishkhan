<?php

namespace App;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    const NEW_TYPE = 'App\Notifications\NewRequisition';
    const CHANGED_TYPE = 'App\Notifications\RequisitionChanged';

    public function getMessageAttribute(): string
    {
        $type = $this->type;
        $requisition = Requisition::find($this->data['requisition_id']);

        $message = '';
        if ($type == self::NEW_TYPE) {
            $message = "new requisition $requisition->en_title";
        }
        if ($type == self::CHANGED_TYPE) {
            $user = User::find($this->data['user']);
            $status = $this->data['status'];
            $verb = $this->status_to_verb($status);

            $message = "$user->name $verb  $requisition->en_title";
        }
        return $message;
    }

    private function status_to_verb($status): string
    {
        $result = '';
        switch ($status) {
            case ACCEPT_ACTION :
                $result = 'accepted';
                break;
            case REJECT_ACTION :
                $result = 'rejected';
                break;
            case ASSIGN_ACTION :
                $result = 'assigned';
                break;
            case HOLD_ACTION :
                $result = 'held';
                break;
            case CLOSE_ACTION :
                $result = 'closed';
                break;
            case OPEN_ACTION :
                $result = 'opened';
                break;
            case FINAL_ACCEPT_ACTION :
                $result = 'final accepted';
                break;
        }
        return $result;
    }

}
