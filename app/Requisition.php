<?php

namespace App;

use Adldap\Laravel\Commands\Import;
use Adldap\Laravel\Facades\Adldap;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Events\RequisitionSent;
use App\Events\RequisitionAccepted;
use App\Classes\RequisitionItems;


class Requisition extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    protected $appends = ['updated_at'];

    /**
     * get human friendly updated at
     */
    public function getUpdatedAtAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['updated_at']))->diffForHumans();
    }


    /**
     * get all approval_progresses for requisition
     */
    public function approval_progresses()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id');
    }

    /**
     * get remaining approval_progresses for requisition
     */
    public function pending_approval_progresses()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id')
            ->where('status', '=', RequisitionStatus::PENDING_STATUS)
            ->orWhere('status', '=', RequisitionStatus::REJECTED_STATUS);
    }

    /**
     * get current progress for requisition
     */
    public function current_approval_progress()
    {
        return $this->pending_approval_progresses()->first();
    }

    /**
     * get accepted progressed for requisition
     */
    public function accepted_approval_progresses()
    {
        return $this->hasMany(RequisitionApprovalProgress::class, 'requisition_id')
            ->where('status', '=', RequisitionStatus::ACCEPTED_STATUS);
    }

    /**
     * get all determiners for requisition
     */
    public function determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_approval_progresses', 'requisition_id', 'determiner_id');
    }

    public function is_last_progress()
    {
        $all_progresses_count = $this->approval_progresses()->count();
        $accepted_progresses_count = $this->accepted_approval_progresses()->count();

        if ($all_progresses_count - $accepted_progresses_count == 1) {
            return true;
        }
        return false;
    }

    /**
     * get remaining determiners for requisition
     */
    public function pending_determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_approval_progresses', 'requisition_id', 'determiner_id')
            ->Where('status', '=', RequisitionStatus::PENDING_STATUS)
            ->orWhere('status', '=', RequisitionStatus::REJECTED_STATUS);
    }

    /**
     * get current determiner for requisition
     */
    public function current_determiner()
    {
        return $this->pending_determiners()->first();
    }

    /**
     * get requisition's owner(creator)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function progresses()
    {
        return $this->hasMany(RequisitionProgress::class, 'requisition_id');
    }

    public function current_progress()
    {
        return $this->progresses()->latest()->first();
    }


    public function open()
    {
        if ($this->current_progress()->status == RequisitionStatus::HOLDING_STATUS) {
            $this->current_progress()->delete();
        }
        $this->update([
            'status' => $this->current_progress()->status
        ]);
        $this->save();
    }

    /**
     * accept requisition
     */
    public function accept($comment = null)
    {
        $c = $this->current_approval_progress()->id;

        // update progress to accepted status.
        $this->current_approval_progress()->update([
            'status' => RequisitionStatus::ACCEPTED_STATUS,
            'determiner_comment' => $comment
        ]);

        if (User::hrAdmin()->id == Auth::id() && $this->approval_progresses()->get()->last()->id == $c) {
            $this->determiner_id = null;
            $this->status = RequisitionStatus::ACCEPTED_STATUS;
            event(new RequisitionAccepted(User::find(Auth::id()), $this->owner));

            $this->create_progress(RequisitionStatus::ACCEPTED_STATUS);

        } else {

            // send the requisition to next determiner.
            $this->determiner_id = $this->current_approval_progress()->determiner_id;
            $sender = User::find(Auth::id());
            $recipient = User::find($this->current_approval_progress()->determiner_id);
            event(new RequisitionSent($sender, $recipient));

        }
        $this->save();
    }


    public function create_progress($status)
    {
        $this->progresses()->firstOrCreate([
            'requisition_id' => $this->id,
            'status' => $status
        ]);
    }


    public function hold()
    {
        $this->update(
            ['status' => RequisitionStatus::HOLDING_STATUS
            ]);
        $this->save();
        $this->create_progress(RequisitionStatus::HOLDING_STATUS);

    }


    public function close()
    {

        $this->update(
            ['status' => RequisitionStatus::CLOSED_STATUS
            ]);
        $this->save();
        $this->create_progress(RequisitionStatus::CLOSED_STATUS);

    }


    /**
     * reject requisition
     */
    public function reject($comment = null)
    {
        // rejecting progress
        // update progress to rejected status.
        $this->current_approval_progress()->update([
            'status' => RequisitionStatus::REJECTED_STATUS,
            'determiner_comment' => $comment
        ]);

      //  if ($this->accepted_approval_progresses()->isNotEmpty()) {
        if ($this->accepted_approval_progresses()) {
            // update requisition's last progress to pending
            $this->accepted_approval_progresses->last()->update([
                'status' => RequisitionStatus::PENDING_STATUS,
            ]);
        }

        // send the requisition to last determiner.
        $this->determiner_id = $this->current_approval_progress()->determiner_id;

        $sender = User::find(Auth::id());


        if ($this->progress_status() == 0) {
            $recipient = User::find($this->current_approval_progress()->determiner_id);

            event(new RequisitionSent($sender, $recipient));

        } elseif ($this->progress_status() == 2) {
            $recipient = $this->owner;
            event(new RequisitionRejected($sender, $recipient));
        }


        $this->save();
    }

    /**
     * reset status on every requisition progress status
     */
    public function reset_determiner_progresses()
    {
        // requisition creator editing
        // send requisition to first determiner
        $this->determiner_id = $this->approval_progresses()->first()->determiner_id;
        // update all approval_progresses to pending status.
        $this->approval_progresses()->update([
            'status' => RequisitionStatus::PENDING_STATUS,
            'determiner_comment' => null
        ]);
    }


    public function getDepartmentAttribute()
    {
        return RequisitionItems::getItems('department')['options'][$this->attributes['department']] ?? 'gg';

    }

    public function getIsFullTimeAttribute()
    {
        return RequisitionItems::getItems('is_full_time')['radios'][$this->attributes['is_full_time']] ?? 'gg';

    }

    public function getIsNewAttribute()
    {
        return RequisitionItems::getItems('is_new')['radios'][$this->attributes['is_new']] ?? 'gg';

    }

    public function getShiftAttribute()
    {
        return RequisitionItems::getItems('shift')['options'][$this->attributes['shift']] ?? 'gg';
    }

    public function getDegreeAttribute()
    {
        return RequisitionItems::getItems('degree')['options'][$this->attributes['degree']] ?? 'gg';

    }

    public function getExperienceYearAttribute()
    {
        return RequisitionItems::getItems('experience_year')['options'][$this->attributes['experience_year']] ?? 'gg';

    }


    public function progress_status()
    {
        $status_array = $this->approval_progresses()->getResults()->map(function ($item) {
            return $item->getOriginal('status');
        })->toArray();

        if (count(array_unique($status_array)) == 1) {
            $status = array_unique($status_array) [0];

        } else {
            $status = 0;
        }
        return $status;
    }


    public function assignments()
    {
        return $this->hasMany(RequisitionAssignment::class, 'requisition_id');
    }

    public function assignment_type()
    {
        $assignment = $this->hasMany(RequisitionAssignment::class, 'requisition_id')->first();
        if ($assignment) {
            return $assignment->type;
        }
        return null;

    }

    public function type_assign_assignment()
    {
        return $this->hasOne(RequisitionAssignment::class, 'requisition_id')->where('type', 'assign');
    }

    public function type_do_assignment()
    {
        return $this->hasOne(RequisitionAssignment::class, 'requisition_id')->where('type', 'do');
    }

    public function prettyAssignments()
    {
        $text = '';

        if (Auth::user()->id == User::hrAdmin()->id && $this->assignment_time()) {

            $text .= "<div class='text-danger'>" . 'Time:' . $this->assignment_time() . "</div><br>";
        }

        foreach ($this->assignments as $asgm) {

            $user = (User::find($asgm->to)->id == Auth::user()->id) ? 'you' : User::find($asgm->to)->name;
            $type_text = ($asgm->type == 'assign') ? 'assign' : 'do';
            $text .= User::find($asgm->from)->name . ' assigned to ' . $user . ' to ' . $type_text . "<br>";
        }
        return $text;
    }

    public function assigned_to_user()
    {
        return $this->belongsToMany(User::class, 'requisition_assignments', 'requisition_id', 'to')
            ->where('requisition_assignments.from', Auth::user()->id)->first();;
    }

    public function assignment_time()
    {
        $last = $this->assignments()->latest()->first();

        if ($this->status == RequisitionStatus::CLOSED_STATUS) {
            return Carbon::parse($this->getOriginal('updated_at'))->longAbsoluteDiffForHumans(Carbon::parse($last->updated_at));
        }
        if (!$last) {
            return false;
        }
        return Carbon::createFromTimeStamp(strtotime($last->updated_at))->diffForHumans();
    }

    public function assign($to, $type)
    {
        // RequisitionAssignment::where('from', Auth::user()->id)->delete();
        // $this->auth_user_assignment_assigned()->delete();
        if ($this->assignments()->count() > 1) {
            $this->type_do_assignment()->delete();
        }
        $this->create_progress(RequisitionStatus::ASSIGN_STATUS);
        $this->update([
            'status' => RequisitionStatus::ASSIGN_STATUS
        ]);
        RequisitionAssignment::updateOrCreate([
            'requisition_id' => $this->id,
            'from' => Auth::user()->id,

        ], [
            'to' => $to,
            'type' => $type
        ]);
    }

    public function setInterviewersAttribute($value)
    {
        $interviewers = null;
        if ($value) {

            $array = [];
            foreach ($value as $k => $v) {
                if (!empty($v[0]) || !empty($v[1])) {
                    $array[$k] = $v;
                }
            }
            $interviewers = (count($array) > 0) ? json_encode($array) : null;
        }

        $this->attributes['interviewers'] = $interviewers;
    }

    public function setCompetencyAttribute($value)
    {
        $this->attributes['competency'] = json_encode($value);
    }

    public function ImportLdapToModel($userPrincipalName)
    {
        $user = Adldap::search()->users()->findBy('userPrincipalName', $userPrincipalName);
        $credentials = [
            'email' => $user->getEmail(),
        ];

// Create the importer:
        $importer = new Import($user, new User(), $credentials);

// Run the importer. The synchronized *unsaved* model will be returned:
        $model = $importer->handle();

// Save the returned model.
        $model->save();
    }

    public static function getOrderedDeterminers($value)
    {
        $value = ($value) ?: [];

        if ($value && $value[0] != User::hrAdmin()->id && !Auth::user()->isHrAdmin()) {
            array_unshift($value, User::hrAdmin()->id);
        }

        if (last($value) != User::hrAdmin()->id) {
            $value[] = User::hrAdmin()->id;
        }

        return $value;
    }

    public function setDeterminerAttribute($value)
    {
        $determiners = self::getOrderedDeterminers($value);
        $sender = User::find(Auth::id());

        $determiner_id = array_values($determiners)[0];
        $recipient = User::find(array_values($determiners)[0]);


        if (config('app.users_provider') == 'ldap') {
            $determiner_id = User::where('email', array_values($determiners)[0])->first()->id;
            $recipient = User::where('email', array_values($determiners)[0])->first();

            foreach ($determiners as $d) {
                $this->ImportLdapToModel($d);
            }
        }

        event(new RequisitionSent($sender, $recipient));

        $this->attributes['determiner_id'] = $determiner_id;

    }

    public function setDeterminerIdAttribute($value)
    {
        $determiner_id = $value;
        if (config('app.users_provider') == 'ldap') {
            $determiner_id = User::where('email', $value)->first()->id;
        }
        $this->attributes['determiner_id'] = $determiner_id;

    }

    public function approver_determiners()
    {
        return $this->belongsToMany(User::class, 'requisition_approval_progresses', 'requisition_id', 'determiner_id')
            ->where('requisition_approval_progresses.role', 'approver');

    }

    public function holding_requisitions()
    {
        return $this->where('status', RequisitionStatus::HOLDING_STATUS);
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'requisition_viewers', 'requisition_id', 'user_id');

    }

    public function requisition_viewers()
    {
        return $this->hasMany(RequisitionViewer::class, 'requisition_id');
    }


}
