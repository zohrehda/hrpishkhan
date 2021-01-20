<?php

namespace App\Policies;

use App\Requisition;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequisitionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function edit(User $user, Requisition $requisition)
    {
        if ($requisition->status == Requisition::PENDING_STATUS) {
            $allowed_ids = [$requisition->owner_id, $requisition->determiner_id];
            if (in_array($user->id, $allowed_ids)) {
                return true;
            }
        }
    }

    public function accept(User $user, Requisition $requisition)
    {
        if ($user->id == $requisition->determiner_id) {
            return true;
        }
    }
    
        public function accepted(User $user,Requisition $requisition)
    {
        if ($requisition->status == 1) {
            return true;
        }
    }

    public function destroy(User $user, Requisition $requisition)
    {
        if ($requisition->status == Requisition::PENDING_STATUS) {
            if ($user->id == $requisition->owner_id) {
                return true;
            }
        }
    }
}
