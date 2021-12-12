<?php

namespace App\Policies;

use App\Requisition;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

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

    public function view(User $user, Requisition $requisition)
    {
        if (in_array($requisition->status, [Requisition::ASSIGN_STATUS, Requisition::ACCEPTED_STATUS, Requisition::CLOSED_STATUS])) {
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

    public function close(User $user, Requisition $requisition)
    {
        if ($requisition->status == Requisition::ASSIGN_STATUS) {
            if ($user->id == User::hrAdmin()->id) {
                return true;
            }

        }

    }

    public function hold(User $user, Requisition $requisition)
    {
        if (Auth::user()->isHrAdmin() && !in_array($requisition->status,[Requisition::HOLDING_STATUS,Requisition::CLOSED_STATUS]) ) {
            return true;
        }
    }

    public function add_viewer(User $user, Requisition $requisition)
    {
        if (Auth::user()->isHrAdmin()) {
            return true;
        }
    }

    public function assign_assign(User $user, Requisition $requisition)
    {
        //if ($requisition->status == Requisition::ACCEPTED_STATUS) {
        if (in_array($requisition->status, [Requisition::ACCEPTED_STATUS, Requisition::ASSIGN_STATUS])) {
            if ($user->id == User::hrAdmin()->id) {
                return true;
            }

        }

    }

    public function assign_do(User $user, Requisition $requisition)
    {

        if ($requisition->status == Requisition::ASSIGN_STATUS) {

            if ($user->user_assigned_to_assign_requisitions->whereIn('id', $requisition->id)->count()) {
                return true;
            }

        }


    }

    public function add_determiners(User $user, Requisition $requisition)
    {
        //return true;
        if ((Auth::user()->id == User::hrAdmin()->id) ||  !$requisition  ) {
            return true;
        }
    }

    public function hr_admin()
    {
        if (Auth::user()->id == User::hrAdmin()->id) {
            return true;
        }
    }

    public function update_determiners(User $user, Requisition $requisition)
    {
        if (Auth::user()->isHrAdmin()  && !$requisition->is_last_progress() ) {
            return true;
        }

    }




}
