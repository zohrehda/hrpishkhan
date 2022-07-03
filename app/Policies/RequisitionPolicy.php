<?php

namespace App\Policies;

use App\Requisition;
use App\RequisitionProgress;
use App\RequisitionStatus;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class RequisitionPolicy
{
    use HandlesAuthorization;


    public function edit(User $user, Requisition $requisition)
    {
        if ($user->is_hr_admin()) {
            return true;
        }
        $allowed_ids = [$requisition->owner_id, $requisition->determiner_id];
        if ($requisition->status == PENDING_STATUS and in_array($user->id, $allowed_ids)) {
            return true;
        }
    }

    public function update_only_titles(User $user, Requisition $requisition)
    {
        return ($user->is_hr_admin() and (!$requisition->current_determiner() or !$requisition->current_determiner()->is_hr_admin()));

    }

    public function accept(User $user, Requisition $requisition)
    {
        if ($user->id == $requisition->determiner_id &&
            ($requisition->status == RequisitionStatus::PENDING_STATUS ||
                $requisition->status == RequisitionStatus::REJECTED_STATUS
            )
        ) {
            return true;
        }
    }

    public function view(User $user, Requisition $requisition)
    {
        return true;
    }

    public function destroy(User $user, Requisition $requisition)
    {
        if ($requisition->status == RequisitionStatus::PENDING_STATUS) {
            if ($user->id == $requisition->owner_id) {
                return true;
            }
        }
    }

    public function close(User $user, Requisition $requisition)
    {
        if ($requisition->status == RequisitionStatus::ASSIGN_STATUS) {
            if ($user->is_hr_admin()) {
                return true;
            }

        }

    }

    public function hold(User $user, Requisition $requisition)
    {
        if ($user->is_hr_admin() && in_array($requisition->status, [RequisitionStatus::PENDING_STATUS, RequisitionStatus::ACCEPTED_STATUS, RequisitionStatus::ASSIGN_STATUS])) {
            return true;
        }
    }

    public function open(User $user, Requisition $requisition)
    {
        if ($user->is_hr_admin() && $requisition->current_progress()->status == RequisitionStatus::HOLDING_STATUS) {
            return true;
        }
    }

    public function add_viewer(User $user, Requisition $requisition)
    {
        if ($user->is_hr_admin()) {
            return true;
        }
    }

    public function assign_assign(User $user, Requisition $requisition)
    {
        //if ($requisition->status == Requisition::ACCEPTED_STATUS) {


        if (in_array($requisition->status, [RequisitionStatus::ACCEPTED_STATUS, RequisitionStatus::ASSIGN_STATUS])) {
            if ($user->id == User::hr_admin()->id) {
                return true;
            }
        }

    }

    public function assign_do(User $user, Requisition $requisition)
    {
        if ($requisition->status == RequisitionStatus::ASSIGN_STATUS) {

            if ($user->user_assigned_to_assign_requisitions->whereIn('id', $requisition->id)->count()) {
                return true;
            }

        }


    }

    /*public function hr_admin()
    {
        if (Auth::user()->id == User::hr_admin()->id) {
            return true;
        }
    }*/

    public function update_determiners(User $user, Requisition $requisition)
    {

        if (Auth::user()->is_hr_admin() &&
            $requisition->current_progress()->status == RequisitionStatus::ADMIN_PRIMARY_PENDING) {
            return true;
        }
    }

    public function final_accept(User $user, Requisition $requisition):bool
    {
        return ($user->is_hr_admin() and $requisition->accepted == 0 and
            $requisition->current_progress()->status != ADMIN_FINAL_PENDING);
    }


}
